<?php
// Clone Objects
// ===============================================================
// 1. shallow clone
class Address {
    protected $city;

    public function setCity($city) { $this->city = $city; }
    public function getcity() { return $this->city; }
}

class Person {
    protected $name;
    protected $address;

    public function __construct() { $this->address = new Address; }
    public function setName($name) { $this->name = $name; }
    public function getName() { return $this->name; }
    public function __call($method, $arguments) {
        if (method_exists($this->address, $method)) {
            return call_user_func_array([$this->address, $method], $arguments);
        }
    }
}

$rasmus = new Person;
$rasmus->setName('Rasmus');
$rasmus->setCity('Sunnyvale');

$zeev = clone $rasmus;
$zeev->setName('Zeev');
$zeev->setCity('Tel Aviv');

// wrong: now they all live in Tel Aviv
// because object (address) is copied by referece
printf("%s lives in %s\n", $rasmus->getName(), $rasmus->getCity());
printf("%s lives in %s\n", $zeev->getName(), $zeev->getCity());

// 2. deep clone
class Person2 extends Person {
    public function __clone() {
        $this->address = clone $this->address;
    }
}

$rasmus = new Person2;
$rasmus->setName('Rasmus');
$rasmus->setCity('Sunnyvale');

$zeev = clone $rasmus;
$zeev->setName('Zeev');
$zeev->setCity('Tel Aviv');

// now works fine
printf("%s lives in %s\n", $rasmus->getName(), $rasmus->getCity());
printf("%s lives in %s\n", $zeev->getName(), $zeev->getCity());