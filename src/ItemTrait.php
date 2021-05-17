<?php
namespace AccelaSearch\ProductMapper;

trait ItemTrait {
    private $identifier;
    private $sku;
    private $url;

    public function getIdentifier(): ?int {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): self {
        $this->identifier = $identifier;
        return $this;
    }

    public function getSku(): ?string {
        return $this->sku;
    }

    public function setSku(string $sku): self {
        $this->sku = $sku;
        return $this;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function setUrl(string $url): self {
        $this->url = $url;
        return $this;
    }
}