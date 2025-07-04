<?php 

namespace App\Traits;

trait PriceTrait {
    public function convertPriceToUSD ($price) {
        return $price / 50 ;
    }
}
