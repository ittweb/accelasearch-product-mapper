<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;

interface StockableInterface {
    public function getStock(): Stock;
}
