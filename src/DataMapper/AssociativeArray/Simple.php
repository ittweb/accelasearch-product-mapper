<?php
/**
 * Associative array data mapper for a simple product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
namespace Ittweb\AccelaSearch\DataMapper\AssociativeArray;

/**
 * Associative array data mapper for a simple product
 *
 * @author     Marco Zanella <mzanella@ittweb.net>
 * @copyright  2020 Ittweb
 * @license    https://opensource.org/licenses/MIT MIT
 * @package    accelasearch-product-mapper
 */
class Simple {
    /**
     * Reference to dataset
     *
     * @var array
     */
    private $dataset;
    
    /**
     * Name of the field used as identifier
     *
     * @var string
     */
    private $identifier_field;
    
    /**
     * Constructor
     *
     * @param array $dataset Reference to dataset
     * @param string $identifier_field Name of field used as identifier
     */
    public function __construct(array &$dataset, string $identifier_field) {
        $this->dataset = $dataset;
        $this->identifier_field = $identifier_field;
    }
    
    /**
     * Creates a new simple product
     *
     * @param \Ittweb\AccelaSearch\Model\Simple $simple Simple product to create
     */
    public function create(\Ittweb\AccelaSearch\Model\Simple $simple) {
        $record = ['id' => 'fake'];
        $this->dataset[$simple[$this->identifier_field]] = $record;
        var_dump($this->dataset);
    }
}