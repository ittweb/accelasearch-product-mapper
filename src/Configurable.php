<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

class Configurable implements ProductInterface {
    use ProductTrait;
    private $variants;

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
        $this->variants = [];
    }

    public function getVariantsAsArray(): array {
        return array_values($this->variants);
    }

    public function addVariant(ProductInterface $product): self {
        $this->variants[] = $product;
        return $this;
    }

    public function removeVariant(ProductInterface $product) {
        $key = false;
        foreach ($this->variants as $k => $variant) {
            if ($variant->getExternalIdentifier() === $product->getExternalIdentifier()) {
                $key = $k;
            }
        }
        if ($key !== false) {
            unset($this->variants[$key]);
        }
        return $this;
    }

    public function clearVariants(): self {
        $this->variants = [];
    }

    public function accept(VisitorInterface $visitor) {
        return $visitor->visitConfigurable($this);
    }
}