<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \PDO;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price as BasePrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

class Price {
    private $dbh;
    private $read_sth;

    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
        $this->prepareStatements();
    }

    public function read(int $product_id): MultiGroupPrice {
        $this->read_sth->execute([':id' => $product_id]);
        $data = [];
        foreach ($this->read_sth->fetchAll() as $record) {
            $data[$record['group_id']][$record['minimum_quantity']][$record['currency']] = [
                'listing_price' => $record['listing_price'],
                'selling_price' => $record['selling_price']
            ];
        }
        $group_price = new MultiGroupPrice();
        foreach ($data as $group_id => $group_data) {
            $tier_price = new MultiTierPrice();
            foreach ($group_data as $minimum_quantity => $tier_data) {
                $currency_price = new MultiCurrencyPrice();
                foreach ($tier_data as $currency => $price_data) {
                    $price = new BasePrice($price_data['listing_price'], $price_data['selling_price']);
                    $currency_price->add($currency, $price);
                }
                $tier_price->add($minimum_quantity, $currency_price);
            }
            $group_price->add($group_id, $tier_price);
        }
        return $group_price;
    }

    private function prepareStatements() {
        $this->read_sth = $this->dbh->prepare(
            'SELECT currency, minimum_quantity, group_id, listing_price, selling_price '
            . 'FROM price_info WHERE product_id = :id'
        );
    }
}
