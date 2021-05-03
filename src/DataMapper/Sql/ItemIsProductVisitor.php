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

class ItemIsProductVisitor implements VisitorInterface {
    public function visitBanner(Banner $item) {
        return false;
    }

    public function visitPage(Page $item) {
        return false;
    }

    public function visitCategoryPage(CategoryPage $item) {
        return false;
    }

    public function visitSimple(Simple $item) {
        return true;
    }

    public function visitVirtual(Virtual $item) {
        return true;
    }

    public function visitDownloadable(Downloadable $item) {
        return true;
    }

    public function visitConfigurable(Configurable $item) {
        return true;
    }

    public function visitBundle(Bundle $item) {
        return true;
    }

    public function visitGrouped(Grouped $item) {
        return true;
    }
}