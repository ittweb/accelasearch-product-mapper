<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

trait SingleItemTrait {
    use ItemTrait;

    public function hasChildren(): bool {
        return false;
    }

    public function getChildrenAsArray(): array {
        return [];
    }
}
