<?php
namespace Ittweb\AccelaSearch\Model;

trait ArrayToJsonTrait {
    public function jsonSerialize() {
        return $this->asArray();
    }
}
