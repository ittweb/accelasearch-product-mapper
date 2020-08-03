<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

trait ProductTrait {
    use ItemTrait;
    use StockableTrait;
    use SellableTrait;

    private $configurable_attributes = [];

    public function getConfigurableAttributesAsArray(): array {
        return $this->configurable_attributes;
    }

    public function addConfigurableAttribute(string $name) {
        if (!is_array($name, $this->configurable_attributes))
            $this->configurable_attributes[] = $name;
    }
}
