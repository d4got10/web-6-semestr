<?php

namespace App\Models;

class ProductRepository
{
    private array $products;
    private ProductDataMapper $dataMapper;

    public function __construct()
    {
        $this->dataMapper = new ProductDataMapper();
    }


    public function GetAll()
    {
        $this->products = $this->dataMapper->GetAll();
        return $this->products;
    }

    public function GetByID($id)
    {
        return $this->dataMapper->GetById($id);
    }

    public function Delete(Product $product){
        $this->dataMapper->Delete($product);
    }

    public function Add(Product $product){
        $this->dataMapper->Add($product);
    }
}