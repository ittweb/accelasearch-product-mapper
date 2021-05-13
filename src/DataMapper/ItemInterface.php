<?php
namespace Ittweb\AccelaSearch\ProductMapper\DataMapper;
use \Ittweb\AccelaSearch\ProductMapper\ItemInterface as Subject;

interface ItemInterface {
    public function create(Subject $item): self;
    public function read(int $identifier): Subject;
    public function update(Subject $item): self;
    public function delete(Subject $item): self;
    public function search();
}