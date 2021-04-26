<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

class Bundle implements ProductInterface {
    use ProductTrait;
    private $products;

    public function __construct(
        string $url,
        string $external_identifier,
        Availability $availability,
        Pricing $pricing,
        ImageInfo $image_info
    ) {
        $this->identifier = null;
        $this->sky = null;
        $this->url = $url;
        $this->availability = $availability;
        $this->pricing = $pricing;
        $this->external_identifier = $external_identifier;
        $this->categories = [];
        $this->image_info = $image_info;
        $this->attributes = [];
        $this->products = [];
    }

    public function getProductsAsArray(): array {
        return array_values($this->products);
    }

    public function addProduct(ProductInterface $product): self {
        $this->products[] = $product;
        return $this;
    }

    public function removeProduct(ProductInterface $product) {
        $key = false;
        foreach ($this->products as $k => $v) {
            if ($v->getExternalIdentifier() === $product->getExternalIdentifier()) {
                $key = $k;
                break;
            }
        }
        if ($key !== false) {
            unset($this->products[$key]);
        }
        return $this;
    }

    public function clearProducts(): self {
        $this->products = [];
    }

    public function accept(VisitorInterface $visitor) {
        return $visitor->visitBundle($this);
    }
}