<?php

function convertPriceToUSD($price)
{
    return $price / 50;
}

function printCurrency($currency)
{
    return "<span style='color:blue'>".strtoupper($currency). "</span>";
    // echo strtoupper($currency);
}
