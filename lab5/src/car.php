<?php

class car
{
    var $engine;
    var $name;
    function __construct($name, $engine)
    {
        $this->name = $name;
        $this->engine = $engine;
    }
    public function printEngine() {
        echo("<b>".$this->engine."</b>");
    }
    public function printName() {
        echo("<b>".$this->name."</b>");
    }
}