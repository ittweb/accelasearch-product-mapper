<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use \Ittweb\AccelaSearch\ProductMapper\Model\Banner;

final class BannerTest extends TestCase {
    public function testAsArray() {
        $item = new Banner();
        $this->assertEquals(['header' => ['type' => 'banner'], 'data' => []], $item->asArray());
    }

    public function testJsonSerialize() {
        $item = new Banner();
        $this->assertEquals('{"header":{"type":"banner"},"data":[]}', json_encode($item));
    }

    public function testSetGet() {
        $item = new Banner();
        $item->name = 'value';
        $this->assertEquals('value', $item->name);
    }

    public function testIsset() {
        $item = new Banner();
        $item->name = 'value';
        $this->assertTrue(isset($item->name));
    }

    public function testUnset() {
        $item = new Banner();
        $item->name = 'value';
        unset($item->name);
        $this->assertFalse(isset($item->name));
    }

    public function testOffsetSetGet() {
        $item = new Banner();
        $item['name'] = 'value';
        $this->assertEquals('value', $item['name']);
    }

    public function testOffsetIsset() {
        $item = new Banner();
        $item['name'] = 'value';
        $this->assertTrue(isset($item['name']));
    }

    public function testOffsetUnset() {
        $item = new Banner();
        $item['name'] = 'value';
        unset($item['name']);
        $this->assertFalse(isset($item['name']));
    }

    public function testGetAttributesAsDictionary() {
        $item = new Banner();
        $item->name = 'value';
        $this->assertEquals(['name' => 'value'], $item->getAttributesAsDictionary());
    }
}
