<?php
namespace Ittweb\AccelaSearch\ProductMapper\Repository\Sql;
use \OutOfBoundsException;
use \Ittweb\AccelaSearch\ProductMapper\Category as Subject;
use \Ittweb\AccelaSearch\ProductMapper\Repository\CategoryInterface;
use \Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql\Connection;
use \Ittweb\AccelaSearch\ProductMapper\DataMapper\Sql\Category as DataMapper;

class Category {
    private $mapper;
    private $categories;

    public function __construct(DataMapper $mapper) {
        $this->mapper = $mapper;
        $this->categories = [];
        foreach ($mapper->search() as $category) {
            $this->categories[$category->getIdentifier()] = $category;
        }
    }

    public static function fromConnection(Connection $connection): self {
        return new Category(DataMapper::fromConnection($connection));
    }

    public function insert(Subject $category): self {
        $this->mapper->create($category);
        $this->categories[$category->getIdentifier()] = $category;
        return $this;
    }

    public function read(int $identifier): Subject {
        if (!isset($this->categories[$identifier])) {
            $this->categories[$identifier] = $this->mapper->read($identifier);
        }
        return $this->categories[$identifier];
    }

    public function update(Subject $category): self {
        $this->mapper->update($category);
        $this->categories[$category->getIdentifier()] = $category;
        return $this;
    }

    public function delete(Subject $category): self {
        $this->mapper->softDelete($category);
        unset($this->categories[$category->getIdentifier()]);
        return $this;
    }

    public function search(callable $criterion): array {
        return array_filter($this->categories, $criterion);
    }
}