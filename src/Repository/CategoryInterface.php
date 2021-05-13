<?php
namespace Ittweb\AccelaSearch\ProductMapper\Repository;
use \Ittweb\AccelaSearch\ProductMapper\Category as Subject;

interface CategoryInterface {
    public function insert(Subject $category): self;
    public function read(int $identifier): Subject;
    public function update(Subject $category): self;
    public function delete(Subject $category): self;
    public function search(callable $criterion): array;
}