<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

trait ArrayToJsonTrait {
    public function jsonSerialize() {
        return $this->asArray();
    }
}
