<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class SimpleArrayMapperTest extends TestCase {
    public function testtest() {
        $dataset = [];
        $identifier_field = 'id';
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->id = 12;
        $entity_mapper = new \Ittweb\AccelaSearch\DataMapper\AssociativeArray\Entity($dataset, $identifier_field);
        $entity_mapper->create($entity);
        var_dump($dataset);
    }
}
