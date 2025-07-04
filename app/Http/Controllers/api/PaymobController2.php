<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;

class PaymobController2 extends Controller
{
    private $apiKey;
    private $integrationId;
    private $iframeId;

    public function __construct()
    {
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
        $this->iframeId = env('PAYMOB_IFRAME_ID');
    }


    public function pay(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:subscriptions,id']);
        $subscription = Subscription::find($request->plan_id);
        $user = auth()->user();

        try {
            // 1. Get auth token
            $authResponse = Http::post('https://accept.paymobsolutions.com/api/auth/tokens', [
                'api_key' => $this->apiKey,
            ]);

            if (!$authResponse->successful()) {
                Log::error('Paymob Auth Error', ['response' => $authResponse->json()]);
                return response()->json(['error' => 'Failed to authenticate with Paymob'], 500);
            }

            $authData = $authResponse->json();

            if (!isset($authData['token'])) {
                Log::error('Paymob Auth Token Missing', ['response' => $authData]);
                return response()->json(['error' => 'Invalid response from Paymob'], 500);
            }

            $authToken = $authData['token'];

            // 2. Create order
            $orderData = [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $subscription->price * 100,
                'merchant_order_id' => $user->id . '_' . $subscription->id . '_' . time(),
                'items' => [],
                
            ];

            Log::info('Attempting to create Paymob order', [
                'order_data' => $orderData,
                'subscription_price' => $subscription->price,
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);

       
            $orderResponse = Http::post('https://accept.paymobsolutions.com/api/ecommerce/orders', $orderData);

            if (!$orderResponse->successful()) {
                Log::error('Paymob Order Creation Error', [
                    'response' => $orderResponse->json(),
                    'status' => $orderResponse->status(),
                    'request_data' => $orderData
                ]);
                return response()->json([
                    'error' => 'Failed to create order with Paymob',
                    'details' => $orderResponse->json()
                ], 500);
            }

            $orderData = $orderResponse->json();

            if (!isset($orderData['id'])) {
                Log::error('Paymob Order ID Missing', ['response' => $orderData]);
                return response()->json(['error' => 'Invalid order response from Paymob'], 500);
            }

            $orderId = $orderData['id'];

            // 3. Generate payment key
            $paymentKeyResponse = Http::post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                'auth_token' => $authToken,
                'amount_cents' => $subscription->price * 100,
                'expiration' => 3600,
                'order_id' => $orderId,
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $user->email,
                    'floor' => 'NA',
                    'first_name' => $user->name,
                    'last_name' => 'User',
                    'street' => 'NA',
                    'building' => 'NA',
                    "phone_number" => $user->phone ?? "01000000000",
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => 'NA',
                    'country' => 'NA',
                    'state' => 'NA',
                ],
                'currency' => 'EGP',
                'integration_id' => $this->integrationId,
            ]);

            if (!$paymentKeyResponse->successful()) {
                Log::error('Paymob Payment Key Error', ['response' => $paymentKeyResponse->json()]);
                return response()->json(['error' => 'Failed to generate payment key'], 500);
            }

            $paymentData = $paymentKeyResponse->json();
            if (!isset($paymentData['token'])) {
                Log::error('Paymob Payment Token Missing', ['response' => $paymentData]);
                return response()->json(['error' => 'Invalid payment key response from Paymob'], 500);
            }

            $paymentToken = $paymentData['token'];

            // 4. Redirect URL
            $iframeUrl = "https://accept.paymobsolutions.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";

            return response()->json([
                'url' => $iframeUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Paymob Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while processing your payment'], 500);
        }
    }

    
    public function callback(Request $request)
    {
        $data = $request->all();
        Log::info('Paymob Callback Data:', $data);

     
        $hmac = $data['hmac'] ?? null;
        if ($hmac) {
           
            $concatenatedString = '';
            
            
            $fields = [
                'amount_cents',
                'created_at',
                'currency',
                'error_occured',
                'has_parent_transaction',
                'id',
                'integration_id',
                'is_3d_secure',
                'is_auth',
                'is_capture',
                'is_refunded',
                'is_standalone_payment',
                'is_voided',
                'order',
                'owner',
                'pending',
                'source_data_pan',
                'source_data_type',
                'success'
            ];

         
            foreach ($fields as $field) {
                if (isset($data['obj'][$field])) {
                    $concatenatedString .= $data['obj'][$field];
                }
            }

            // إنشاء HMAC باستخدام المفتاح السري
            $calculatedHmac = hash_hmac('sha512', $concatenatedString, env('PAYMOB_HMAC_SECRET'));

            // مقارنة HMAC المرسل مع HMAC المحسوب
            if (!hash_equals($calculatedHmac, $hmac)) {
                Log::error('❌ Invalid HMAC signature', [
                    'calculated' => $calculatedHmac,
                    'received' => $hmac,
                    'concatenated' => $concatenatedString
                ]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }
        }

        $success = $data['obj']['success'] ?? false;

        if (!$success) {
            Log::warning('❌ Payment not successful or missing success flag', ['success' => $success]);
            return response()->json(['message' => 'Payment failed'], 400);
        }

        Log::info('✅ Payment successful, processing subscription');

   
        $merchantOrderId = $data['obj']['order']['merchant_order_id'] ?? null;

        if (!$merchantOrderId) {
            Log::error('❌ Missing merchant_order_id in callback', [
                'data' => $data['obj']['order'] ?? 'No order data'
            ]);
            return response()->json(['message' => 'Invalid order ID'], 400);
        }

        Log::info('Found merchant_order_id', ['merchant_order_id' => $merchantOrderId]);

        if (str_contains($merchantOrderId, '_')) {
            [$userId, $subscriptionId] = explode('_', $merchantOrderId);

    
            $user = User::find($userId);
            $subscription = Subscription::find($subscriptionId);

            if (!$user || !$subscription) {
                Log::error('❌ User or Subscription not found', [
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId
                ]);
                return response()->json(['message' => 'User or subscription not found'], 404);
            }

            try {
                UserSubscription::create([
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId,
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($subscription->duration_days),
                    'status' => 'active'
                ]);

                Log::info('✅ Subscription created successfully', [
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId
                ]);

                return response()->json(['message' => 'Payment successful and subscription created'], 200);
            } catch (\Exception $e) {
                Log::error('❌ Error creating subscription', [
                    'error' => $e->getMessage(),
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId
                ]);
                return response()->json(['message' => 'Error creating subscription'], 500);
            }
        }

        Log::error('❌ Invalid merchant_order_id format', ['merchant_order_id' => $merchantOrderId]);
        return response()->json(['message' => 'Invalid order format'], 400);
    }
}
