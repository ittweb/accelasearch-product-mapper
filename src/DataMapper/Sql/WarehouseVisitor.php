<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\VisitorInterface;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\Virtual;

class WarehouseVisitor implements VisitorInterface {
    public function visitPhysical(Physical $warehouse) {
        return [
            'is_virtual' => 0,
            'latitude' => $warehouse->getLatitude(),
            'longitude' => $warehouse->getLongitude()
        ];
    }

    public function visitVirtual(Virtual $warehouse) {
        return [
            'is_virtual' => 1,
            'latitude' => null,
            'longitude' => null
        ];
    }
}