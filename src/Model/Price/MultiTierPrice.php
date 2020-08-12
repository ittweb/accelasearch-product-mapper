<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;
use \JsonSerializable;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayConvertibleInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayToJsonTrait;

class MultiTierPrice implements ArrayConvertibleInterface, JsonSerializable {
    use ArrayToJsonTrait;
    private $tiers;

    public function __construct() {
        $this->tiers = [];
    }

    public function asArray(): array {
        $tiers = [];
        foreach ($this->tiers as $quantity => $tier) {
            $tiers[sprintf('%.0f', $quantity)] = $tier->asArray();
        }
        return $tiers;
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
