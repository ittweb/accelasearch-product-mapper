<?php
/**
 * A group of entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * A group of entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Bundle extends Product {
    /**
     * Array of entities part of the bundle
     *
     * @var array
     */
    private $products = [];

    /**
     * Returns array of products in this bundle
     *
     * @return array Products in this bundle
     */
    public function getProducts(): array {
        return $this->products;
    }

    /**
     * Adds a product to this bundle
     *
     * @param Product $product Product to add
     */
    public function addProduct(Product $product) {
        $this->products[] = $product;
    }

    /**
     * Removes every product from this bundle
     */
    public function clearProducts() {
        $this->products = [];
    }

    /**
     * Accepts a visitor
     *
     * @param EntityVisitorInterface $visitor The visitor to accept
     */
    public function accept(EntityVisitorInterface $visitor) {
        $visitor->visitBundle($this);
    }
}