<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;

class Physical implements WarehouseInterface {
    private $identifier, $latitude, $longitude;

    public function __construct(string $identifier, float $latitude, float $longitude) {
        $this->identifier = $identifier;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function isVirtual(): bool {
        return false;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }
}
