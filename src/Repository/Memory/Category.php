<?php
namespace Ittweb\AccelaSearch\ProductMapper\Repository\Memory;
use \OutOfBoundsException;
use \BadFunctionCallException;
use \Ittweb\AccelaSearch\ProductMapper\Category as Subject;
use \Ittweb\AccelaSearch\ProductMapper\Repository\CategoryInterface;

class Category {
    private $categories;

    public function __construct() {
        $this->categories = [];
    }

    public function insert(Subject $category): self {
        if (is_null($category->getIdentifier())) {
            throw new BadFunctionCallException('Missing identifier');
        }
        $this->categories[$category->getIdentifier()] = $category;
        return $this;
    }

    public function read(int $identifier): Subject {
        if (!isset($this->categories[$identifier])) {
            throw new OutOfBoundsException('No category with identifier ' . $identifier);
        }
        return $this->categories[$identifier];
    }

    public function update(Subject $category): self {
        return $this->insert($category);
    }

    public function delete(Subject $category): self {
        unset($this->categories[$category->getIdentifier()]);
        return $this;
    }

    public function search(callable $criterion): array {
        return array_values(array_filter($this->categories, $criterion));
    }
}