<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;

class Unlimited implements QuantityInterface {
    public function isUnlimited(): bool {
        return true;
    }
}
