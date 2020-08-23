<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;

class Limited implements QuantityInterface {
    private $quantity;

    public function __construct(float $quantity) {
        $this->quantity = $quantity;
    }

    public function isUnlimited(): bool {
        return false;
    }

    public function getQuantity(): float {
        return $this->quantity;
    }

    public function setQuantity(float $quantity) {
        $this->quantity = $quantity;
    }
}
