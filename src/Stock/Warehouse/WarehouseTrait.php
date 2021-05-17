<?php
namespace AccelaSearch\ProductMapper\Stock\Warehouse;

trait WarehouseTrait {
    private $identifier;
    private $label;

    public function getIdentifier(): ?int {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): self {
        $this->identifier = $identifier;
        return $this;
    }

    public function getLabel(): string {
        return $this->label;
    }

    public function setLabel(string $label): self {
        $this->label = $label;
        return $this;
    }
}