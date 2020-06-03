<?php
/**
 * Stock information
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Stock information
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class StockInfo {
    /**
     * Item quantity
     * 
     * @var float
     */
    private $quantity;
    
    /**
     * Tells whether quantity is unlimited
     *
     * @var bool
     */
    private $is_unlimited;

    /**
     * Default constructor
     *
     * @param float $quantity Item quantity, default 0.0
     * @param bool $is_unlimited True if and only if item is available in unlimited quantity, default true
     */
    public function __construct(float $quantity = 0.0, bool $is_unlimited = true) {
        $this->quantity = $quantity;
        $this->is_unlimited = $is_unlimited;
    }

    /**
     * Tells whether item is available in unlimited quantity
     *
     * @return bool True if and only if item is available in unlimited quantity
     */
    public function isUnlimited(): bool {
        return $this->is_unlimited;
    }

    /**
     * Returns quantity
     *
     * @return float Qauntity
     */
    public function getQuantity(): float {
        return $this->quantity;
    }

    /**
     * Makes item available in unlimited quantity
     */
    public function makeUnlimited() {
        $this->is_unlimited = true;
    }


    /**
     * Makes item available in limited quantity
     */
    public function makeLimited() {
        $this->is_unlimited = false;
    }

    /**
     * Sets item quantity
     *
     * @param float $quantity Quantity
     */
    public function setQuantity(float $quantity) {
        $this->quantity = $quantity;
    }
}
