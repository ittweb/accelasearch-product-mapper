<?php
namespace AccelaSearch\ProductMapper;
use \AccelaSearch\ProductMapper\Stock\Availability;
use \AccelaSearch\ProductMapper\Price\Pricing;

class Simple implements ProductInterface {
    use ProductTrait;

    public function __construct(
        string $url,
        string $external_identifier,
        Availability $availability,
        Pricing $pricing,
        ImageInfo $image_info
    ) {
        $this->identifier = null;
        $this->sku = null;
        $this->url = $url;
        $this->availability = $availability;
        $this->pricing = $pricing;
        $this->external_identifier = $external_identifier;
        $this->categories = [];
        $this->image_info = $image_info;
        $this->attributes = [];
    }

    public function accept(VisitorInterface $visitor) {
        return $visitor->visitSimple($this);
    }
}