<?php

namespace App\Models;

use PDO;

class ProductDataMapper
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=mysimpledb', 'root', 'root');
    }


    public function GetById($id) : ?array
    {
        $sql = "SELECT * FROM product WHERE ProductID=:ProductID";
        $statement = $this->pdo->prepare($sql);

        $statement->bindParam(":ProductID", $id);

        $statement->execute();
        $results = $statement->fetchAll();

        $func = fn($x) => self::ConvertToProduct($x);

        return array_map($func, $results);
    }

    public function GetAll() : ?array
    {
        $sql = "SELECT * FROM product";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $func = fn($x) => self::ConvertToProduct($x);

        return array_map($func, $results);
    }

    public function Delete(Product $product)
    {
        $sql = "DELETE FROM product WHERE ProductID=:ProductID";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':ProductID', $product->ID);
        $statement->execute();
    }

    public function Add(Product $product)
    {
        $sql = "INSERT INTO product (ProductName, ProductVendor, ProductAmount) VALUES(:ProductName, :ProductVendor, :ProductAmount)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(":ProductName", $product->Name);
        $statement->bindParam(":ProductVendor", $product->Vendor);
        $statement->bindParam(":ProductAmount", $product->Amount);
        $statement->execute();
    }

    private function ConvertToProduct($result) : Product
    {
        $product = new Product();

        $product->ID = $result['ProductID'];
        $product->Name = $result['ProductName'];
        $product->Vendor = $result['ProductVendor'];
        $product->Amount = $result['ProductAmount'];

        return $product;
    }
}