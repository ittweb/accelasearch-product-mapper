<?php
/**
 * Information about price of a sellable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Information about price of a sellable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class PriceInfo {
    /**
     * Associative array of price information of customer groups
     *
     * @var array
     */
    private $customer_groups = [];

    /**
     * Returns price info
     *
     * @return array Associative array of price info per customer group
     */
    public function getPrices(): array {
        return $this->customer_groups;
    }

    /**
     * Returns price for customer group
     *
     * @param string $customer_group_id Identifier of customer group
     * @return GroupPrice Price info for given customer group
     * @throw \OutOfBoundsException if price for customer group is not set
     */
    public function getPricesForCustomerGroup(string $customer_group_id): GroupPrice {
        if (!array_key_exists($customer_group_id, $this->customer_groups)) {
            throw new \OutOfBoundsException("No customer group \"$customer_group_id\" defined.");
        }

        return $this->customer_groups[$customer_group_id];
    }

    /**
     * Sets price info for a customer group
     *
     * @param string $customer_group_id Identifier of customer group
     * @param GroupPrice $group_price Price info for customer group
     */
    public function setPricesForCustomerGroup(string $customer_group_id, GroupPrice $group_price) {
        $this->customer_groups[$customer_group_id] = $group_price;
    }

    /**
     * Removes price info for a customer group
     *
     * @param string $customer_group_id Identifier of customer group
     */
    public function unsetPricesForCustomerGroup($customer_group_id) {
        unset($this->customer_groups[$customer_group_id]);
    }
}
