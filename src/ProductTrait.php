<?php
namespace Ittweb\AccelaSearch\ProductMapper;

trait ProductTrait {
    use ItemTrait;
    use StockableTrait;
    use SellableTrait;

    private $external_identifier;
    private $categories;
    private $image_info;
    private $attributes;

    public function getExternalIdentifier(): string {
        return $this->external_identifier;
    }

    public function setExternalIdentifier(string $identifier): self {
        $this->external_identifier = $identifier;
        return $this;
    }

    public function getCategoriesAsArray(): array {
        return array_values($this->categories);
    }

    public function addCategory(Category $category): self {
        $this->categories[$category->getExternalIdentifier()] = $category;
        return $this;
    }

    public function removeCategory(Category $category): self {
        unset($this->categories[$category->getExternalIdentifier()]);
        return $this;
    }

    public function clearCategories(): self {
        $this->categories = [];
        return $this;
    }

    public function getImageInfo(): ImageInfo {
        return $this->image_info;
    }

    public function getAttributesAsArray(): array {
        return array_values($this->attributes);
    }

    public function addAttribute(Attribute $attribute): self {
        $this->attributes[$attribute->getName()] = $attribute;
        return $this;
    }

    public function removeAttribute(Attribute $attribute): self {
        unset($this->attributes[$attribute->getName()]);
        return $this;
    }

    public function clearAttributes(): self {
        $this->attributes = [];
        return $this;
    }
}