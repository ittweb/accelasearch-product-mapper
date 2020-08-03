<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfo as Stock;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice as Price;

class Grouped implements ProductInterface {
    use ProductTrait;

    private $components;

    public function __construct(Stock $stock, Price $price) {
        $this->components = [];
            $this->setStock($stock);
            $this->setPrice($price);
    }

    public function asArray(): array {
        $components = [];
        foreach ($this->components as $product) {
            $components[] = $product->asArray();
        }
        return [
            'header' => [
                'type' => 'grouped'
            ],
            'data' => $this->getAttributesAsDictionary(),
            'configurable_attributes' => $this->getConfigurableAttributesAsArray(),
            'Groupeds' => $components,
            'warehouses' => $this->getStock()->asArray(),
            'pricing' => $this->getPrice()->asArray()
        ];
    }

    public function getComponentsAsArray(): array {
        return $this->components;
    }

    public function addComponent(ProductInterface $product) {
        $this->components[] = $product;
    }

    public function accept(ItemVisitorInterface $visitor) {
        $visitor->visitGrouped($this);
    }
}
