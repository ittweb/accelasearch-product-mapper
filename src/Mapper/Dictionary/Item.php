<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary;
use \Ittweb\AccelaSearch\ProductMapper\Model\ItemInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\ProductInterface;
use \Ittweb\AccelaSearch\ProductMapper\Model\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Model\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Downloadable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Model\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Model\Grouped;
use \Ittweb\AccelaSearch\ProductMapper\Model\Page;
use \Ittweb\AccelaSearch\ProductMapper\Model\Category;
use \Ittweb\AccelaSearch\ProductMapper\Model\Banner;
use \BadFunctionCallException;
use \OutOfBoundsException;

class Item {
    private $stock_mapper;
    private $price_mapper;

    public function __construct(Stock $stock_mapper, Price $price_mapper) {
        $this->stock_mapper = $stock_mapper;
        $this->price_mapper = $price_mapper;
    }

    public function create(ItemInterface $model): array {
        return $model->asArray();
    }

    public function read(array $data): ItemInterface {
        if (!isset($data['header']['type'])) {
            throw new BadFunctionCallException('Missing parameter header.type.');
        }
        switch ($data['header']['type']) {
            case 'simple': return $this->readSimple($data);
            case 'virtual': return $this->readVirtual($data);
            case 'downloadable': return $this->readDownloadable($data);
            case 'configurable': return $this->readConfigurable($data);
            case 'bundle': return $this->readBundle($data);
            case 'grouped': return $this->readGrouped($data);
            case 'page': return $this->readPage($data);
            case 'category': return $this->readCategory($data);
            case 'banner': return $this->readBanner($data);
            default:
                throw new OutOfBoundsException('Unknown item type "' . $data['header']['type'] . '"');
        }
    }

    private function readAttributes(ItemInterface $item, array $data) {
        if (!isset($data['data'])) {
            return;
        }
        foreach ($data['data'] as $key => $value) {
            $item->$key = $value;
        }
    }

    private function readConfigurableAttributes(ProductInterface $product, array $data) {
        if (!isset($data['configurable_attributes'])) {
            return;
        }
        foreach ($data['configurable_attributes'] as $attribute_name) {
            $product->addConfigurableAttribute($attribute_name);
        }
    }

    private function readConfigurations(Configurable $configurable, array $data) {
        if (!isset($data['variants'])) {
            return;
        }
        foreach ($data['variants'] as $configuration) {
            $configurable->addConfiguration($this->read($configuration));
        }
    }

    private function readComponents(Bundle $bundle, array $data) {
        if (!isset($data['bundles'])) {
            return;
        }
        foreach ($data['bundles'] as $component) {
            $bundle->addComponent($this->read($component));
        }
    }

    private function readProduct(ProductInterface $product, array $data) {
        $this->readAttributes($product, $data);
        $this->readConfigurableAttributes($product, $data);
    }

    private function readSimple(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Simple($stock, $price);
        $this->readProduct($item, $data);
        return $item;
    }

    private function readVirtual(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Virtual($stock, $price);
        $this->readProduct($item, $data);
        return $item;
    }

    private function readDownloadable(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Downloadable($stock, $price);
        $this->readProduct($item, $data);
        return $item;
    }

    private function readConfigurable(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Configurable($stock, $price);
        $this->readProduct($item, $data);
        $this->readConfigurations($item, $data);
        return $item;
    }

    private function readBundle(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Bundle($stock, $price);
        $this->readProduct($item, $data);
        $this->readComponents($item, $data);
        return $item;
    }

    private function readGrouped(array $data): ItemInterface {
        $stock = $this->stock_mapper->read($data['warehouses']);
        $price = $this->price_mapper->read($data['pricing']);
        $item = new Grouped($stock, $price);
        $this->readProduct($item, $data);
        $this->readComponents($item, $data);
        return $item;
    }

    private function readPage(array $data): ItemInterface {
        $item = new Page();
        $this->readAttributes($item, $data);
        return $item;
    }

    private function readCategory(array $data): ItemInterface {
        $item = new Category();
        $this->readAttributes($item, $data);
        return $item;
    }

    private function readBanner(array $data): ItemInterface {
        $item = new Banner();
        $this->readAttributes($item, $data);
        return $item;
    }
}
