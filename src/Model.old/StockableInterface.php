<?php
/**
 * Interface of a stockable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\Model;

/**
 * Interface of a stockable entity
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
interface StockableInterface {
    /**
     * Returns stock info
     *
     * @return array Stock information
     */
    public function getStockInfo(): array;

    /**
     * Returns stock information for a warehouse
     *
     * @param string $warehouse_id Identifier of warehouse
     * @return StockInfo Stock information
     * @throw \OutOfBoundsException if no stock info for given warehouse is set
     */
    public function getStockInfoForWarehouse(string $warehouse_id): StockInfo;

    /**
     * Sets stock info for a warehouse
     *
     * @param string $warehouse_id Warehouse identifier
     * @param StockInfo Stock info for warehouse
     */
    public function addStockInfo(string $warehouse_id, StockInfo $stock_info);

    /**
     * Removes stock info for a warehouse
     *
     * @param string $warehouse_id Warehouse identifier
     */
    public function removeStockInfoForWarehouse(string $warehouse_id);
}
