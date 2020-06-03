<?php
/**
 * Price info for a customer group
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Price info for a customer group
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class GroupPrice {
    /**
     * Array of price info for quantity tiers
     *
     * @var array
     */
    private $tiers = [];

    /**
     * Returns price info per quantity tier
     *
     * @return array Price info per tier
     */
    public function getTiers(): array {
       return $this->tiers;
    }

    /**
     * Return price info tier for given quantity
     *
     * @param float $quantity Quantity
     * @return TierPrice Price for tier
     */
    public function getTierByQuantity(float $quantity): TierPrice {
        return $this->tiers[$this->findTierIndex($quantity)];
    }

    /**
     * Sets a tier price info for a minimum quantity
     *
     * @param float $quantity Minimum quantity
     * @param TierPrice Tier price info
     */
    public function setTierForQuantity(float $quantity, TierPrice $tier_price) {
        $this->tiers[(string) $quantity] = $tier_price;
    }

    /**
     * Removes tier price info for a minimum quantity
     *
     * @param float $quantity Minimum quantity
     */
    public function unsetTierForQuantity(float $quantity) {
        unset($this->tiers[$this->findTierIndex($quantity)]);
    }

    /**
     * Returns index of tier price for given quantity
     *
     * @param float $quantity Quantity to search
     * @param string Index of tier
     * @throw \OutOfBoundsException if there are no tiers defined
     */
    private function findTierIndex(float $quantity): string {
        if (empty($this->tiers)) {
            throw new \OutOfBoundsException("Not tiers defined.");
        }

        $min_quantities = array_map('floatval', array_keys($this->tiers));
        sort($min_quantities);

        $target_min_quantity = array_keys($this->tiers)[0];
        foreach ($min_quantities as $tier_min_quantity) {
            if ($tier_min_quantity > $quantity) {
                break;
            }
            $target_min_quantity = $tier_min_quantity;
        }

        return (string) $target_min_quantity;
    }
}
