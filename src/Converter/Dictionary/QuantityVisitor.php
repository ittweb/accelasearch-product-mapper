<?php
namespace Ittweb\AccelaSearch\ProductMapper\Converter\Dictionary;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\VisitorInterface;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Quantity\Unlimited;

class QuantityVisitor implements VisitorInterface {
    public function visitLimited(Limited $quantity) {
        return [
            'type' => 'limited',
            'quantity' => $quantity->getQuantity()
        ];
    }

    public function visitUnlimited(Unlimited $quantity) {
        return [
            'type' => 'unlimited'
        ];
    }
}