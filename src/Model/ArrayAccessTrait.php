<?php
namespace Ittweb\AccelaSearch\Model;
use \OutOfBoundException;

trait ArrayAccessTrait {
    private $data;

    public function offsetExists($name): bool {
        return array_key_exists($name, $this->data);
    }

    public function offsetGet($offset) {
        if (!array_key_exists($offset, $this->data)) {
            throw new OutOfBoundsException("Attribute \"$offset\" is not defined.");
        }
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
}
