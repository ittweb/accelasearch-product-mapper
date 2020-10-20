<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

trait SingleItemTrait {

    public function hasChildren(): bool {
        return false;
    }

    public function getChildrenAsArray(): array {
        return [];
    }
}
