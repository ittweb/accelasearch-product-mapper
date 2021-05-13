<?php
namespace Ittweb\AccelaSearch\ProductMapper\Converter\Dictionary;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\VisitorInterface;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Warehouse\Virtual;

class WarehouseVisitor implements VisitorInterface {
    public function visitPhysical(Physical $warehouse) {
        return [
            'type' => 'physical',
            'id' => $warehouse->getIdentifier(),
            'label' => $warehouse->getLabel(),
            'latitude' => $warehouse->getLatitude(),
            'longitude' => $warehouse->getLongitude()
        ];
    }

    public function visitVirtual(Virtual $virtual) {
        return [
            'type' => 'virtual',
            'id' => $warehouse->getIdentifier(),
            'label' => $warehouse->getLabel()
        ];
    }
}