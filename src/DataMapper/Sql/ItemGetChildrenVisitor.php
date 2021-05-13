<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql;
use \Ittweb\AccelaSearch\ProductMapper\VisitorInterface;
use \Ittweb\AccelaSearch\ProductMapper\Banner;
use \Ittweb\AccelaSearch\ProductMapper\Page;
use \Ittweb\AccelaSearch\ProductMapper\CategoryPage;
use \Ittweb\AccelaSearch\ProductMapper\Simple;
use \Ittweb\AccelaSearch\ProductMapper\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Downloadable;
use \Ittweb\AccelaSearch\ProductMapper\Configurable;
use \Ittweb\AccelaSearch\ProductMapper\Bundle;
use \Ittweb\AccelaSearch\ProductMapper\Grouped;

class ItemGetChildrenVisitor implements VisitorInterface {
    public function visitBanner(Banner $item) {
        return [];
    }

    public function visitPage(Page $item) {
        return [];
    }

    public function visitCategoryPage(CategoryPage $item) {
        return [];
    }

    public function visitSimple(Simple $item) {
        return [];
    }

    public function visitVirtual(Virtual $item) {
        return [];
    }

    public function visitDownloadable(Downloadable $item) {
        return [];
    }

    public function visitConfigurable(Configurable $item) {
        return $item->getVariantsAsArray();
    }

    public function visitBundle(Bundle $item) {
        return $item->getProductsAsArray();
    }

    public function visitGrouped(Grouped $item) {
        return $item->getProductsAsArray();
    }
}