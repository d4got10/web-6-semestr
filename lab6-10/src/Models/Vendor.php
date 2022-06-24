<?php

namespace App\Models;
use PDO;

class Vendor
{
    public string $Name;
    public string $Password;
    public ?string $Token;


    public function __construct()
    {
    }

    public static function GetByName($name) : ?Vendor
    {
        $sql = "SELECT * FROM vendor WHERE VendorName=:VendorName";
        $statement = self::GetPDO()->prepare($sql);

        $statement->bindParam(":VendorName", $name);

        $statement->execute();
        $results = $statement->fetchAll();

        $func = fn($x) => self::ConvertToVendor($x);
        $vendors = array_map($func, $results);

        if(count($vendors) > 0)
            return $vendors[0];

        return null;
    }

    public static function GetAll() : ?array
    {
        $sql = "SELECT * FROM vendor";
        $statement = self::GetPDO()->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $func = fn($x) => self::ConvertToVendor($x);

        return array_map($func, $results);
    }

    public static function DeleteByName($name)
    {
        $sql = "DELETE FROM vendor WHERE VendorName=:VendorName";
        $statement = self::GetPDO()->prepare($sql);
        $statement->bindParam(':VendorName', $name);
        $statement->execute();
    }

    public static function Add($name, $password)
    {
        $sql = "INSERT INTO vendor (VendorName, VendorPassword) VALUES(:VendorName, :VendorPassword)";
        $statement = self::GetPDO()->prepare($sql);
        $statement->bindParam(":VendorName", $name);
        $statement->bindParam(":VendorPassword", $password);
        $statement->execute();
    }

    public function Save()
    {
        $sql = "UPDATE vendor SET VendorPassword=:VendorPassword, Token=:Token WHERE VendorName=:VendorName)";
        $statement = self::GetPDO()->prepare($sql);
        $statement->bindParam(":VendorName", $this->Name);
        $statement->bindParam(":VendorPassword", $this->Password);
        $statement->bindParam(":Token", $this->Token);
        $statement->execute();
    }

    private static function ConvertToVendor($result) : Vendor
    {
        $vendor = new Vendor();

        $vendor->Name = $result['VendorName'];
        $vendor->Password = $result['VendorPassword'];
        $vendor->Token = $result['Token'];

        return $vendor;
    }

    private static function GetPDO() : PDO
    {
        return new PDO('mysql:host=localhost;dbname=mysimpledb', 'root', 'root');
    }
}