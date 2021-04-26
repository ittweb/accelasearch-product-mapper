<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

class Downloadable extends Virtual {
    public function __construct(
        string $url,
        string $external_identifier,
        Availability $availability,
        Pricing $pricing,
        ImageInfo $image_info
    ) {
        parent::__construct($url, $external_identifier, $availability, $pricing, $image_info);
    }

    public function accept(VisitorInterface $visitor) {
        return $visitor->visitDownloadable($this);
    }
}