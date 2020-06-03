<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class EntityTest extends TestCase {
    public function testOffsetExists() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity['name'] = 'value';
        $this->assertTrue(isset($entity['name']));
    }
    
    public function testIsSet() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->name = 'value';
        $this->assertTrue(isset($entity->name));
    }
    
    public function testOffsetSetGet() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity['name'] = 'value';
        $this->assertEquals($entity['name'], 'value');
    }
    
    public function testSetGet() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->name = 'value';
        $this->assertEquals($entity->name, 'value');
    }
    
    public function testOffsetUnset() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity['name'] = 'value';
        unset($entity['name']);
        $this->assertFalse(isset($entity['name']));
    }
    
    public function testUnset() {
        $entity = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->name = 'value';
        unset($entity->name);
        $this->assertFalse(isset($entity->name));
    }
}
