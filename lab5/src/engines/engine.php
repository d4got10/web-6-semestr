<?php
namespace engines;
class engine
{
    var $name;
    var $power;
    function __construct($name, $power)
    {
        $this->name = $name;
        $this->power = $power;
    }
    public function printName() {
        echo("<b>".$this->name."</b>");
    }
    public function printPower(){
        echo("<b>".$this->power."</b>");
    }
}