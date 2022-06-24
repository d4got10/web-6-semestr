<?php

namespace App;

use App\Controllers\MainController;
use App\Models\Product;
use App\Models\ProductRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Application{
    private MainController $controller;
    private ProductRepository $productRepository;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__,1) . '\src\Views\\');
        $twig = new Environment($loader);
        $this->controller = new MainController($twig);
        $this->productRepository = new ProductRepository();
    }

    public function run()
    {
        if(isset($_GET['logout'])){
            $this->controller->LogOut();
            return;
        }

        if(isset($_GET['login'])){
            $login = $_GET['login'];

            if(isset($_GET['password'])) {
                $password = $_GET['password'];
                if(isset($_GET['register'])){
                    $this->controller->Register($login, $password);
                }else {
                    $this->controller->TryLogIn($login, $password);
                }
            }else{
                $this->controller->DisplayLogin();
                return;
            }
        }

        $this->controller->Execute();

        if (isset($_GET['getId'])){
            $this->controller->Display($this->productRepository->GetById($_GET['id']));
        }

        if (isset($_GET['delete']))
            $this->productRepository->Delete($this->productRepository->GetById($_GET['delId'])[0]);


        if (isset($_GET['add']) && isset($_GET['name']))
        {
            $product = new Product();
            $product->Name = $_GET['name'];
            $product->Vendor = $this->controller->DecodeToken($_COOKIE['token'])['login'];
            $product->Amount = $_GET['amount'];

            $this->productRepository->Add($product);
        }


        if (isset($_GET['getAll']))
            $this->controller->Display($this->productRepository->GetAll());
        if (isset($_GET['getById']))
            $this->controller->DisplayGetById();
        if (isset($_GET['deleteById']))
            $this->controller->DisplayDelete();
        if (isset($_GET['add']))
            $this->controller->DisplayAdd();
    }
}