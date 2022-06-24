<?php

namespace App\Models;
use PDO;

class Product
{
    public int $ID;
    public string $Name;
    public int $Amount;
    public string $Vendor;


    public function __construct()
    {
    }
}