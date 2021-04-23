<?php
class Std {
    private $a;
    protected $b;

    function __get($name) {
        return $name;
    }
}

