<?php
namespace App\Controllers;

use App\Models\Vendor;
use Firebase\JWT\Key;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Firebase\JWT\JWT;

class MainController{
    private Environment $response;
    const salt = 'e8811fd0011bc374342ea655cf1a173661f3533a
                    39ba98a7e73d0fd0744a3d703926710b328b19fe
                    e46941b0e083af6c886dddb94985e6679c2bfebd
                    e1e5420a8b923d442809393f6917414bfea737b9
                    72bbd8e87c751abe38c209196cfe6cc78402ba74
                    2e9bde09cbcdb2c6c93705fcfb9b0f32081887e6
                    7dc1c4d5c83849d6c6944b3e000854d582589158
                    574e675fff968fe04d650c274da83ff21a5aabdb
                    d1091f13fc843072afd4c130a67f7057b828b246
                    732b43741065e9300e1a1787510f024e8297ed02';

    public function __construct(Environment $response){
        $this->response=$response;
    }
    public function Execute()
    {
        $params = ['logged' => false];
        if(isset($_COOKIE['token']))
        {
            $login = $this->DecodeToken($_COOKIE['token'])['login'];
            $vendor = Vendor::GetByName($login);
            if($vendor != null){
                $params['logged'] = true;
                $params['name'] = $vendor->Name;
            }
        }

        echo $this->response->render('main.html.twig', $params);
    }
    public function Display($data){
        echo $this->response->render('all.html.twig',['data'=>$data]);
    }
    public function DisplayGetById(){
        echo $this->response->render('getById.html.twig');
    }
    public function DisplayDelete(){
        echo $this->response->render('delete.html.twig');
    }
    public function DisplayAdd(){
        echo $this->response->render('add.html.twig');
    }
    public function DisplayLogin(){
        echo $this->response->render('login.html.twig');
    }

    public function TryLogIn($login, $password) {
        $vendor = Vendor::GetByName($login);
        $hash = sha1($password . self::salt);

        if($vendor != null &&  strcmp($vendor->Password, $hash) === 0){
            $vendor->Token = $this->EncodeToken($vendor);
            $vendor->Save();

            setcookie('token', $vendor->Token, time() + 36000, '/');

            header('Location: ');
            return;
        }
        echo 'Неверное имя пользователя или пароль';
    }

    public function Register($login, $password)
    {
        $vendor = Vendor::GetByName($login);
        if($vendor != null){
            echo 'Пользователь с таким именем уже существует';
            return;
        }

        $hash = sha1($password . self::salt);

        Vendor::Add($login, $hash);
        $this->TryLogIn($login, $password);
    }

    public function LogOut()
    {
        setcookie('token', '', 0, '/');
        $this->DisplayLogin();
    }

    public function EncodeToken(Vendor $vendor) : string {
        $data = [
          'login' => $vendor->Name
        ];
        return JWT::encode($data, self::salt, 'HS256');
    }

    public function DecodeToken($token) : array {
        return (array)JWT::decode($token, new Key(self::salt, 'HS256'));
    }
}