<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;

interface WarehouseInterface {
    public function isVirtual(): bool;
    public function getIdentifier(): string;
}
