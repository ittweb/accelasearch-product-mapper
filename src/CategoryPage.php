<?php
namespace Ittweb\AccelaSearch\ProductMapper;

class CategoryPage extends Page {

    public function __construct(string $url) {
        $this->identifier = null;
        $this->sku = null;
        $this->url = $url;
    }

    public function accept(VisitorInterface $visitor) {
        return $visitor->visitCategoryPage($this);
    }
}