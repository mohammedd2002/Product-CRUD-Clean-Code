<?php 

namespace App\Interfaces;

interface PriceInterface {
    public function convertPriceToUSD ($price);
}