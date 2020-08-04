<?php
/**
 * Interface of a bulk data mapper
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\DataMapper;

/**
 * Interface of a bulk data mapper
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
interface BulkInterface {
    /**
     * Creates a set of entities
     *
     * @param array $entities Set of entities to create
     */
    public function bulkCreate(array $entities);
    
    /**
     * Updates a set of entities
     *
     * @param array $entities Set of entities to update
     */
    public function bulkUpdate(array $entities);
    
    /**
     * Deletes a set of entities
     *
     * @param array $entities Set of entities to delete
     */
    public function bulkDelete(array $entities);
}