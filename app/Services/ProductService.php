<?php

namespace App\Services;

use App\Interfaces\PriceInterface;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;

class  ProductService
{

    protected $productRepository;
    protected $priceService;
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PriceInterface $priceService
    ) {
        $this->productRepository = $productRepository;
        $this->priceService = $priceService;
    }

    public function allPaginated()
    {
        return $this->productRepository->allPaginated(10);
    }

    public function create($data)
    {
        // $data['price_usd'] = convertPriceToUSD($data['price']);
        $data['price_usd'] = $this->priceService->convertPriceToUSD($data['price']);
        // dd($data);
        return $this->productRepository->create($data);
    }

    public function update($data, Product $product)
    {
        return $this->productRepository->update($data, $product);
    }

    public function delete($product)
    {
        return $this->productRepository->delete($product);
    }
}
