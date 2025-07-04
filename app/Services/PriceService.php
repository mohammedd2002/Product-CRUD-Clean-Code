<?php 

namespace App\Services;

use App\Interfaces\PriceInterface;

class PriceService implements PriceInterface {
    public function convertPriceToUSD ($price) {
        return $price / 50 ;
    }
}
