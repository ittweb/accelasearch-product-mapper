<?php
namespace Ittweb\AccelaSearch\ProductMapper\Model\Stock;

class Virtual implements WarehouseInterface {
    private $identifier;

    public function __construct(string $identifier) {
        $this->identifier = $identifier;
    }

    public function isVirtual(): bool {
        return true;
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }
}
