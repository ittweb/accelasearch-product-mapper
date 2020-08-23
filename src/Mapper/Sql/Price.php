<?php
namespace Ittweb\AccelaSearch\ProductMapper\Mapper\Sql;
use \PDO;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price as BasePrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

class Price {
    private $dbh;
    private $create_sth;
    private $read_sth;
    private $delete_external_sth;

    public function __construct(PDO $dbh) {
        $this->dbh = $dbh;
        $this->prepareStatements();
    }

    public function create(MultiGroupPrice $multi_group, int $product_id, string $product_external_id, int $shop_id) {
        foreach ($multi_group->asDictionary() as $group_id => $multi_tier) {
            foreach ($multi_tier->asDictionary() as $minimum_quantity => $multi_currency) {
                foreach ($multi_currency->asDictionary() as $currency => $price) {
                    $this->create_sth->execute([
                        ':product_id' => $product_id,
                        ':product_external_id' => $product_external_id,
                        ':shop_id' => $shop_id,
                        ':group_id' => $group_id,
                        ':currency' => $currency,
                        ':minimum_quantity' => $minimum_quantity,
                        ':listing_price' => $price->getListingPrice(),
                        ':selling_price' => $price->getSellingPrice()
                    ]);
                }
            }
        }
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

    public function deleteByExternalId(string $external_id, int $shop_id) {
        $this->delete_external_sth->execute([
            ':product_external_id' => $external_id,
            ':shop_id' => $shop_id
        ]);
    }

    private function prepareStatements() {
        $this->create_sth = $this->dbh->prepare(
            'INSERT INTO price_info(product_id, product_external_id, shop_id, group_id, currency, minimum_quantity, listing_price, selling_price) '
          . 'VALUES(:product_id, :product_external_id, :shop_id, :group_id, :currency, :minimum_quantity, :listing_price, :selling_price)'
        );
        $this->read_sth = $this->dbh->prepare(
            'SELECT currency, minimum_quantity, group_id, listing_price, selling_price '
          . 'FROM price_info WHERE product_id = :id'
        );
        $this->delete_external_sth = $this->dbh->prepare(
            'DELETE FROM price_info WHERE product_external_id = :product_external_id AND shop_id = :shop_id'
        );
    }
}
