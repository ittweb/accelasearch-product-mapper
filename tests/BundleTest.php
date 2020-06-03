<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class BundleTest extends TestCase {
    public function testGetProducts() {
        $entity = new \Ittweb\AccelaSearch\Model\Bundle();
        $this->assertTrue(empty($entity->getProducts()));
    }
    
    public function testAddProduct() {
        $entity = new \Ittweb\AccelaSearch\Model\Bundle();
        $simple = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->addProduct($simple);
        $this->assertEquals(count($entity->getProducts()), 1);
    }
    
    public function testClearProduct() {
        $entity = new \Ittweb\AccelaSearch\Model\Bundle();
        $simple = new \Ittweb\AccelaSearch\Model\Simple();
        $entity->addProduct($simple);
        $entity->clearProducts();
        $this->assertTrue(empty($entity->getProducts()));
    }
}
