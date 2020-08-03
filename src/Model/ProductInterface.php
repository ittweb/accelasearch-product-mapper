<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;

interface ProductInterface extends ItemInterface, StockableInterface, SellableInterface {
    public function getConfigurableAttributesAsArray(): array;
    public function addConfigurableAttribute(string $name);
}
