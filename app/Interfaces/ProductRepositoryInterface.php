<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function allPaginated($perPage);
    public function create($data);
    public function update($data , $product);
    public function delete($product);
}