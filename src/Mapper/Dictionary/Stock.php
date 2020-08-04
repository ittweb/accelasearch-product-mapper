<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo;
use \BadFunctionCallException;
use \OutOfBoundsException;

class Stock {
    public function create(StockInfo $stock): array {
        return $stock->asArray();
    }

    public function read(array $data): StockInfo {
        $stock = new StockInfo();
        foreach ($data as $entry) {
            $warehouse_id = $entry['warehouse_id'];
            $warehouse = $entry['is_virtual']
                       ? new Virtual($warehouse_id)
                       : new Physical($warehouse_id, $entry['position'][0], $entry['position'][1]);
            $quantity = $entry['is_unlimited']
                      ? new Unlimited()
                      : new Limited($entry['quantity']);
            $stock->add($warehouse, $quantity);
        }
        return $stock;
    }
}
