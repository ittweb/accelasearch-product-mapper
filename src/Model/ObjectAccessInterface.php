<?php
namespace Ittweb\AccelaSearch\Model;

interface ObjectAccessInterface {
    public function __isset(string $name);
    public function __get(string $name);
    public function __set(string $name, $value);
    public function __unset(string $name);
}
