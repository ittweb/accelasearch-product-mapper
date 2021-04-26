<?php
namespace Ittweb\AccelaSearch\ProductMapper;

interface ItemInterface {
    public function getIdentifier(): ?int;
    public function setIdentifier(int $identifier): self;
    public function getSku(): ?string;
    public function setSku(string $sku): self;
    public function getUrl(): string;
    public function setUrl(string $url): self;
    public function accept(VisitorInterface $visitor);
}