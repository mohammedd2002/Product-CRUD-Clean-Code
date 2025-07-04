<?php 

namespace App\Repositories;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function allPaginated($perPage){
        return Product::latest()->paginate($perPage);    
    }
    public function create($data){
       return Product::create($data);
    }
    public function update($data ,  $product){
        return $product->update($data);
    }
    public function delete($product){
        return $product->delete();
    }
}

