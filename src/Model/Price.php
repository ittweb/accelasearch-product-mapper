<?php
/**
 * Listing and selling price information
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Listing and selling price information
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Price {
    /**
     * Listing price
     *
     * @var float
     */
    private $listing_price;
    /**
     * Selling price
     *
     * @var float
     */
    private $selling_price;

    /**
     * Constructor
     *
     * @param float $listing_price Listing price
     * @param float|null $selling_price Selling price, same as listing price if set to null (default null)
     */
    public function __construct(float $listing_price, float $selling_price = null) {
        $this->listing_price = $listing_price;
        $this->selling_price = !is_null($selling_price) ? $selling_price : $listing_price;
    }

    /**
     * Returns listing price
     *
     * @return float Listing price
     */
    public function getListingPrice(): float {
        return $this->listing_price;
    }

    /**
     * Returns selling price
     *
     * @return float Selling price
     */
    public function getSellingPrice(): float {
        return $this->selling_price;
    }

    /**
     * Sets listing price
     *
     * @param float $listing_price Listing price
     */
    public function setListingPrice(float $listing_price) {
        $this->listing_price = $listing_price;
    }

    /**
     * Sets selling price
     *
     * @param float $selling_price Selling price
     */
    public function setSellingPrice(float $selling_price) {
        $this->selling_price = $selling_price;
    }
}
