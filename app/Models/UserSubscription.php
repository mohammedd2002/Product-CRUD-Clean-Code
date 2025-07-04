<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscription'; // اسم الجدول

    protected $guarded = ['id'];
     // يمكنك تحديد الحقول التي يمكن تعديلها

     
    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع الاشتراك
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
