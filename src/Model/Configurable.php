<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;

class Configurable implements ProductInterface {
    use ProductTrait;

    private $configurations;

    public function __construct(Stock $stock, Price $price) {
        $this->configurations = [];
            $this->setStock($stock);
            $this->setPrice($price);
    }

    public function asArray(): array {
        $configurations = [];
        foreach ($this->configurations as $product) {
            $configurations[] = $product->asArray();
        }
        return [
            'header' => [
                'type' => 'configurable'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'variants' => $configurations,
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function getConfigurationsAsArray(): array {
        return $this->configurations;
    }

    public function addConfiguration(ProductInterface $product) {
        $this->configurations[] = $product;
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitConfigurable($this);
    }
}
