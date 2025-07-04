<?php 

namespace App\Services;

use App\Interfaces\PriceInterface;

class PriceDiscountService implements PriceInterface {

    protected $discount;
    public function __construct(float $discount) 
    {
        $this->discount = $discount;
    }

    public function convertPriceToUSD ($price) {
        $discounted = $price - ($price * $this->discount);
        return $discounted / 50 ;
    }
}
