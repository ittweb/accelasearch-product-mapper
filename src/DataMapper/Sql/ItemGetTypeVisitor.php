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

class ItemGetTypeVisitor implements VisitorInterface {
    public function visitBanner(Banner $item) {
        return 90;
    }

    public function visitPage(Page $item) {
        return 91;
    }

    public function visitCategoryPage(CategoryPage $item) {
        return 92;
    }

    public function visitSimple(Simple $item) {
        return 10;
    }

    public function visitVirtual(Virtual $item) {
        return 60;
    }

    public function visitDownloadable(Downloadable $item) {
        return 61;
    }

    public function visitConfigurable(Configurable $item) {
        return 30;
    }

    public function visitBundle(Bundle $item) {
        return 40;
    }

    public function visitGrouped(Grouped $item) {
        return 20;
    }
}