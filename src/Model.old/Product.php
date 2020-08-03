<?php
/**
 * A product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * A product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
abstract class Product extends Entity implements StockableInterface, SellableInterface {
    use StockableTrait;
    use SellableTrait;

    /**
     * Default constructor.
     */
    public function __construct() {
        $this->price_info = new PriceInfo();
    }
}
