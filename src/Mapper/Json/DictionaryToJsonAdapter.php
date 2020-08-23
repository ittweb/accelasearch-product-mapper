<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Json;
use \JsonSerializable;

class DictionaryToJsonAdapter implements JsonInterface {
    private $dictionary_mapper;

    public function __construct($dictionary_mapper) {
        $this->dictionary_mapper = $dictionary_mapper;
    }

    public function create(JsonSerializable $model): string {
        return json_encode($this->dictionary_mapper->create($model));
    }

    public function read(string $data): JsonSerializable {
        return $this->dictionary_mapper->read(json_decode($data, true));
    }
}
