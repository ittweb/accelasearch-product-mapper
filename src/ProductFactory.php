<?php
namespace Ittweb\AccelaSearch\ProductMapper;
use \Ittweb\AccelaSearch\ProductMapper\Stock\Availability;
use \Ittweb\AccelaSearch\ProductMapper\Price\Pricing;

class ProductFactory {
    public function createSimple(
        string $url,
        string $external_identifier
    ): Simple {
        return new Simple($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }

    public function createVirtual(
        string $url,
        string $external_identifier
    ): Virtual {
        return new Virtual($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }

    public function createDownloadable(
        string $url,
        string $external_identifier
    ): Downloadable {
        return new Downloadable($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }

    public function createConfigurable(
        string $url,
        string $external_identifier
    ): Configurable {
        return new Configurable($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }

    public function createBundle(
        string $url,
        string $external_identifier
    ): Bundle {
        return new Bundle($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }

    public function createGrouped(
        string $url,
        string $external_identifier
    ): Grouped {
        return new Grouped($url, $external_identifier, new Availability(), new Pricing(), new ImageInfo());
    }
}