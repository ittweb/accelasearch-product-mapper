<?php
/**
 * Trait for stockable entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Trait for stockable entities
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
trait StockableTrait {
    /**
     * Array of warehouses with stock info
     *
     * @var array
     */
    private $stock_info = [];

    /**
     * Returns stock info
     *
     * @return array Stock information
     */
    public function getStockInfo(): array {
        return $this->stock_info;
    }

    /**
     * Returns stock information for a warehouse
     *
     * @param string $warehouse_id Identifier of warehouse
     * @return StockInfo Stock information
     * @throw \OutOfBoundsException if no stock info for given warehouse is set
     */
    public function getStockInfoForWarehouse(string $warehouse_id): StockInfo {
        if (!array_key_exists($warehouse_id, $this->stock_info)) {
            throw new \OutOfBoundsException("No stock info for warehouse \"$warehouse_id\".");
        }

        return $this->stock_info[$warehouse_id];
    }

    /**
     * Sets stock info for a warehouse
     *
     * @param string $warehouse_id Warehouse identifier
     * @param StockInfo Stock info for warehouse
     */
    public function addStockInfo(string $warehouse_id, StockInfo $stock_info) {
        $this->stock_info[$warehouse_id] = $stock_info;
    }

    /**
     * Removes stock info for a warehouse
     *
     * @param string $warehouse_id Warehouse identifier
     */
    public function removeStockInfoForWarehouse(string $warehouse_id) {
        unset($this->stock_info[$warehouse_id]);
    }
}
