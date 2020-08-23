<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Json;
use \JsonSerializable;

interface JsonInterface {
    public function create(JsonSerializable $model): string;
    public function read(string $data): JsonSerializable;
}
