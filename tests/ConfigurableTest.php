<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class ConfigurableTest extends TestCase {
    public function testGetConfigurations() {
        $entity = new \Ittweb\AccelaSearch\Model\Configurable();
        $simple = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->addConfiguration($simple);
        $this->assertFalse(empty($entity->getConfigurations()));
    }
    
    public function testGetConfigurableAttribute() {
        $entity = new \Ittweb\AccelaSearch\Model\Configurable();
        $entity->addConfigurableAttribute('size');
        $this->assertFalse(empty($entity->getConfigurableAttributes()));
    }
    
    public function testUnsetConfigurableAttribute() {
        $entity = new \Ittweb\AccelaSearch\Model\Configurable();
        $entity->addConfigurableAttribute('size');
        $entity->unsetConfigurableAttribute('size');
        $this->assertTrue(empty($entity->getConfigurableAttributes()));
    }
    
    public function testClearConfigurations() {
        $entity = new \Ittweb\AccelaSearch\Model\Configurable();
        $simple = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->addConfiguration($simple);
        $entity->clearConfigurations();
        $this->assertTrue(empty($entity->getConfigurations()));
    }
    
    public function testClearConfigurableAttribute() {
        $entity = new \Ittweb\AccelaSearch\Model\Configurable();
        $entity->addConfigurableAttribute('size');
        $entity->clearConfigurableAttributes();
        $this->assertTrue(empty($entity->getConfigurableAttributes()));
    }
}
