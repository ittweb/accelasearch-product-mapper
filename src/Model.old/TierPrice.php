<?php
/**
 * Price information in different currencies
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Price information in different currencies
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class TierPrice {
    /**
     * Price information in different currencies
     *
     * @var array
     */
    private $prices = [];

    /**
     * Returns price information
     *
     * @return array Price information
     */
    public function getPrices(): array {
        return $this->prices;
    }

    /**
     * Returns price for a specific currency
     *
     * @param string $currency Currency
     * @return Price price information
     * @throw \OutOfBoundsException if no price is set for given currency
     */
    public function getPriceForCurrency(string $currency): Price {
        $currency = trim(strtoupper($currency));

        if (!array_key_exists($currency, $this->prices)) {
            throw new \OutOfBoundsException("No price defined for currency \"$currency\".");
         }

         return $this->prices[$currency];
    }

    /**
     * Sets price for a currency
     *
     * @param string $currency Currency
     * @param Price $price Price
     */
    public function setPriceForCurrency(string $currency, Price $price) {
        $currency = trim(strtoupper($currency));
        $this->prices[$currency] = $price;
    }

    /**
     * Removes price for a currency
     *
     * @param string $currency Currency
     */
    public function unsetPriceForCurrency(string $currency) {
        $currency = trim(strtoupper($currency));
        unset($this->prices[$currency]);
    }
}
