<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;

class MultiTierPrice {
    private $tiers;

    public function __construct() {
        $this->tiers = [];
    }

    public function asDictionary(): array {
        return $this->tiers;
    }

    public function add(float $quantity, MultiCurrencyPrice $tier) {
        $this->tiers[(string) $quantity] = $tier;
    }

    public function remove(float $quantity) {
        unset($this->tiers[(string) $quantity]);
    }
}
