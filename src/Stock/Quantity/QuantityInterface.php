<?php
namespace Ittweb\AccelaSearch\ProductMapper\Stock\Quantity;

interface QuantityInterface {
    public function accept(VisitorInterface $visitor);
}