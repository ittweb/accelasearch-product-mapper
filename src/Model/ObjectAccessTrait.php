<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \OutOfBoundException;

trait ObjectAccessTrait {
    private $data = [];

    public function __isset(string $name): bool {
        return array_key_exists($name, $this->data);
    }

    public function __get(string $name) {
        if (!array_key_exists($name, $this->data)) {
            throw new OutOfBoundsException("Attribute \"$name\" is not defined.");
        }
        return $this->data[$name];
    }

    public function __set(string $name, $value) {
        $this->data[$name] = $value;
    }

    public function __unset(string $name) {
        unset($this->data[$name]);
    }
}
