<?php
spl_autoload_register(function ($className) {
    $path = dirname(__DIR__ ) . '/src/' . str_replace("\\", "/", $className) . '.php';
    require_once $path;
});

use car;
use engines\engine;

$myengine = new engine("Dviglo", 150);
$mycar = new car("Mira", "Dviglo");

echo ("Аппарат Анрдюхи - ");
$mycar -> printName();

echo "<br>";

echo ("Мира - аппарат с двигателем ");
$myengine->printName();

echo "<br>";

echo ("Сила движка - ");
$myengine->printPower();