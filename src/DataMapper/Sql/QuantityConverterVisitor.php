<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\VisitorInterface;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\Unlimited;

class QuantityConverterVisitor implements VisitorInterface {
    public function visistLimited(Limited $quantity) {
        return [
            'is_unlimited' => false,
            'quantity' => $quantity->getQuantity()
        ];
    }

    public function visitUnlimited(Unlimited $quantity) {
        return [
            'is_unlimited' => true,
            'quantity' => 0
        ];
    }
}