<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayConvertibleInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ArrayToJsonTrait;
use \JsonSerializable;

class StockInfo implements ArrayConvertibleInterface, JsonSerializable {
    use ArrayToJsonTrait;
    private $warehouses;

    public function __construct() {
        $this->warehouses = [];
    }

    public function getStockAsDisctionary(): array {
        return $this->warehouses;
    }

    public function asArray(): array {
        $stock = [];
        foreach ($this->warehouses as $entry) {
            $warehouse = $entry['warehouse'];
            $quantity = $entry['quantity'];
            $data = [
                'warehouse_id' => $warehouse->getIdentifier(),
                'is_virtual' => $warehouse->isVirtual(),
                'is_unlimited' => $quantity->isUnlimited()
            ];
            if (!$warehouse->isVirtual()) {
                $data['position'] = [$warehouse->getLatitude(), $warehouse->getLongitude()];
            }
            if (!$quantity->isUnlimited()) {
                $data['quantity'] = $quantity->getQuantity();
            }
            $stock[] = $data;
        }
        return $stock;
    }

    public function add(WarehouseInterface $warehouse, QuantityInterface $quantity) {
        $this->warehouses[$warehouse->getIdentifier()] = [
            'warehouse' => $warehouse,
            'quantity' => $quantity
        ];
    }

    public function remove(WarehouseInterface $warehouse) {
        unset($this->warehouses[$warehouse->getIdentifier()]);
    }
}
