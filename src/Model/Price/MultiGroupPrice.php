<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Price;
use \JsonSerializable;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayConvertibleInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayToJsonTrait;

class MultiGroupPrice implements JsonSerializable, ArrayConvertibleInterface {
    use ArrayToJsonTrait;
    private $groups;

    public function __construct() {
        $this->groups = [];
    }

    public function asArray(): array {
        $price = [];
        foreach ($this->groups as $identifier => $group) {
            $price[$identifier] = $group->asArray();
        }
        return $price;
    }

    public function asDictionary(): array {
        return $this->groups;
    }

    public function add(string $group_identifier, MultiTierPrice $group) {
        $this->groups[$group_identifier] = $group;
    }

    public function remove(string $group_identifier) {
        unset($this->groups[$group_identifier]);
    }
}
