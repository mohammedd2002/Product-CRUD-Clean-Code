<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\PriceTrait;
use Illuminate\Support\Str;
use App\Services\PriceService;
use App\Services\ProductService;
use App\Http\Requests\ProductRequest;
use Spatie\MediaLibrary\Support\MediaStream;

class ProductController extends Controller
{
  

    protected $productService; //service
    public function __construct(ProductService $productService ) //service
    {
        $this -> productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->allPaginated();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }


    public function store(ProductRequest $request)
    {
        $product = $this->productService->create( $request->validated());
        $product->addMediaFromRequest('media')->toMediaCollection('products'); //media
        return redirect()->route('products.index');
    }


    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }


    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }


    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->update($request->validated(), $product);
        return to_route('products.index');
    }

   
    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        return redirect()->route('products.index');
    }

}
