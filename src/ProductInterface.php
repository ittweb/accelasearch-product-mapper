<?php
namespace Ittweb\AccelaSearch\ProductMapper;

interface ProductInterface extends ItemInterface {
    public function getExternalIdentifier(): string;
    public function setExternalIdentifier(string $identifier): self;
    public function getCategoriesAsArray(): array;
    public function addCategory(Category $category): self;
    public function removeCategory(Category $category): self;
    public function clearCategories(): self;
    public function getImageInfo(): ImageInfo;
    public function getAttributesAsArray(): array;
    public function addAttribute(Attribute $attribute): self;
    public function removeAttribute(Attribute $attribute): self;
    public function clearAttributes(): self;
}