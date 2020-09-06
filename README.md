# AccelaSearch Product Mapper
PHP product data mapper for easy integration with AccelaSearch.

## Overview

AccelaSearch deploys an intermediate SQL-like database, called *collector*, to store product information, which are then used to configure and populate search engine and results. Data about items must be stored in the proper format (see the *AccelaSearch - Custom Integration* document for details). *AccelaSearch - Product Mapper* is a PHP library which exposes an *Abstract Data Type* (ADT) to represent all of the different types of product supported by AccelaSearch, as well as a convenient set of data mapper to convert from/to different formats, including JSON, PHP dictionaries and the intermediate SQL format.

AccelaSearch - Product Mapper allows to define product data in an abstract, syntax-friendly way and send/retrieve information from the collector database in order to simplify integration with third-parties systems or CMS, without requiring any understanding of the underlying collector database schema. Note, however, that the Product Mapper will operate on products as whole, atomic entities and will be unaware of CMS-specify behaviors which could speed up the synchronization process (see Limitations for an example).

## Requirements

AccelaSearch - Product Mapper is self-contained, it requires an up-to-date version of PHP to run.

Provided unit tests can be run with [PHPUnit](https://phpunit.de/) , and code documentation can be generated with [PHPDcoumentor](https://www.phpdoc.org/).

## Installation

Recommended installation is through [Composer](https://getcomposer.org/) :

```bash
composer require ittweb/accelasearch-product-mapper
```

Manual installation is possible by cloning or downloading this repository:

```bash
git clone https://github.com/ittweb/accelasearch-product-mapper.git
```

```bash
wget https://github.com/ittweb/accelasearch-product-mapper/archive/master.zip
```

## Usage

A valid API key is needed to authenticate the application and retrieve collector database credentials. API key can be obtained after activating an AccelaSearch account. `my-api-key` will be used as an example.

### Product Types

AccelaSearch supports different types of product (see *AccelaSearch - Custom Integration* for a  comprehensive guide), each one having a direct corresponding model in the Product Mapper abstract data type system. Every type of item implements *ItemInterface*, which allows attributes to be dynamically added and defined without a prior schema. Common e-commerce products also implements *StockableInterface* and *SellableInterface* which allow setting/retrieving information about stock (multi-warehouse, virtual or physical, limited or unlimited quantity) and price (multi customer group, multi tier, multi currency and discerning between listing and selling price).

#### Stock information

Every item must be stored in a *warehouse*, either physical (of which geographical coordinates are known) or virtual (of which coordinates are not relevant). Item can be available in limited quantity, or unlimited. The following example shows how to define a stock information:

```php
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Limited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Unlimited;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Virtual;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\Physical;
use \Ittweb\AccelaSearch\ProductMapper\Model\Stock\StockInfro;

// Stock information
$stock = new StockInfo();

// Two warehouse are available
$warehouse_1 = new Virtual('virtual-warehouse');
$warehouse_2 = new Physical('physical-shop', 45.0, 0.0);  // Latitude and longitude

// Item is available in unlimited quantity in 'virtual-warehouse'
$quantity = new Unlimited();
$stock->add($warehouse_1, $quantity);

// Same item is available in limited quantity, 15 units, in 'physical-shop'
$quantity = new Limited(15.0);
$stock->add($warehouse_2, $quantity);
```

**Note:** Warehouses' identifies (eg. *virtual-warehouse*, *physical-shop*) must have been previously inserted into the collector database.

#### Price Information

Prices in AccelaSearch are multi group, multi tier, multi currency and support different values for listing and selling. The following example shows how to define a price:

```php
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\Price;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiCurrencyPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiTierPrice;
use \Ittweb\AccelaSearch\ProductMapper\Model\Price\MultiGroupPrice;

$group_price = new MultiGroupPrice();

// Preparing data for customer group with id 'default'
$group_default = new MultiTierPrice();

// Preparing data for group 'default', order of 0 items or more
$zero_or_more = new MultiCurrencyPrice();

// Preparig data for goup 'default', 0 items or more, EUR currency
$price = new Price(42.0);      // Listing and selling price is 42.00
$price->setSellingPrice(21.0); // Changes selling price to 21.00
$zero_or_more->add('EUR', $price);

// Preparig data for goup 'default', 0 items or more, USD currency
$price = new Price(50.0);      // Listing and selling price is 42.00
$price->setSellingPrice(25.0); // Changes selling price to 21.00
$zero_or_more->add('USD', $price);

// Preparing data for group 'default', order of 10 items or more
$ten_or_more = new MultiCurrencyPrice();
$price = new Price(35.0);
$ten_or_more->add('EUR', $price); // Defined for EUR only: USD will pay the full price

// Storing tier data
$group_default->add(0.0, $zero_or_more);
$group_default->add(10.0, $ten_or_more);

// Storing data for group 'default'
$group_price->add('default', $group_default);

// Other group info can be added as needed
$other_group = new MultiTierPrice();
// ...
$group_price->add('other', $other_group);
```

**Note:** Customer group identifiers must have been previously inserted in the collector database.

#### Simple Product

A simple product can be defined by setting a stock information, a price, and adding attributes needed:

```php
use Ittweb\AccelaSearch\ProductMapper\Model\Simple;

$stock = new StockInfo();
$price = new MultiGroupPrice();
// ... defines stock an price info as in previous examples

$product = new Simple($stock, $price);
$product->sku = 'P001';
$product->image = array('http://shop.com/p001/img1.jpg', 'http://shop.com/p001/img2.jpg');
$product->weight = 0.52;

// Dictionary syntax can be used as well
$product['name'] = 'Some Skirt';
$product['height'] = 0.38;
$product['category'] = 'Fashion/Woman/Skirt';
```

**Note:** It is not required to actually populate stock and price info. Items without stock data will have an "undefined" stock, which is not the same as a quantity of 0. For instance, filters and searches querying for stock availability will not work on undefined stock, but will on 0 stock. The same applies to price information.

#### Configurable Product

Similar to a simple product, but also support definition of *variants*, also known as *configurations*:

```php
use Ittweb\AccelaSearch\ProductMapper\Model\Configurable;

$stock = new StockInfo();
$price = new MultiGroupPrice();
$product = new Configurable($stock, $price);
$product->sku = 'P-002';

// Configurations can be added one at a time
$stock_for_red_variant = new StockInfo();
$price_for_red_variant = new PriceInfo();
$red_variant = new Simple();
$red_variant->sku = 'P-002-red';
$red_variant->color = 'red';
$product->addConfiguration($red_variant);

// Blue variant, for the sake of example
$stock_for_blue_variant = new StockInfo();
$price_for_blue_variant = new PriceInfo();
$blue_variant = new Simple();
$blue_variant->sku = 'P-002-blue';
$blue_variant->color = 'blue';
$product->addConfiguration($blue_variant);
```

**Note:** Configurable products do not need to have variants defined, although this is precisely their purpose.

#### Bundle and Grouped Product

Analogous to a configurable product, bundles and grouped products allow to specify components:

```php
use Ittweb\AccelaSearch\ProductMapper\Model\Bundle;

$stock = new StockInfo();
$price = new MultiGroupPrice();
$bundle = new Bundle($stock, $price);
$product->sku = 'P-003';

// Components can be added one at a time
$stock = new StockInfo();
$price = new PriceInfo();
$component_1 = new Simple();
$component_1->sku = 'P-073';
$product->addComponent($component_1);

// Another component, for the sake of example
$stock = new StockInfo();
$price = new PriceInfo();
$component_2 = new Simple();
$component_2->sku = 'P-512';
$product->addComponent($component_2);
```

**Note:** Bundle and grouped products do not need to have components defined, although this is precisely their purpose.

#### Recursive Types

Products referring other products (such as variants for *Configurable* or components for *Grouped, Bundles*) can accept any instance of an *ItemInterface*. As a consequence, it is possible to nest configurable products as components of bundles and vice-versa, or configurable products within configurable products and so on, allowing arbitrarily complex product data structures.

### Obtaining Collector Database Credential

The SQL item mapper class exposes an utility method to retrieve connection credential from an API key:

```php
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Sql\Item as ItemMapper;

$api_key = 'my-api-key';
$credential = ItemMapper::getCredentialFromApiKey($api_key);
echo json_encode($credential, JSON_PRETTY_PRINT);
```

which outputs:

```json
{
    "name": "collector_42",
    "username": "user_42",
    "password": "super-secure-password",
    "hostname": "collector1.accelasearch.net"
}
```

Credential must be used to create a valid instance of a `\PDO` object:

```php
$dbh = new \PDO(
    'mysql:dbname=' . $credential['name'] . ';host=' . $credential['hostname'],
    $credendial['username'],
    $credendial['password']
);
```

The library requires fetch mode to produce *at least* an associative array, which is compatible with PDO's default settings. Although not required, it is strongly recommend setting PDO's error mode to throw exception:

```php
// Unnecessary with PDO's default settings
$dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
```

### Instantiating the SQL Mapper

Instances of the SQL mapper operates *per shop*, hence a valid AccelaSearch shop identifier must be provided (see the *AccelaSearch - Custom Integration* to learn how to retrieve the list of shop identifiers). The name of the attribute used as identifier by the local system or CMS (tipically *id*, *sku*, etc.) must also be provided:

```php
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Sql\Item as ItemMapper;

$mapper = new ItemMapper($dbh, 42, 'sku');
```

Optionally, if the local system supports configurable attributes or categories, the name of the attributes representing them can be specified by:

```php
$mapper->addCategoryField('category');
$mapper->addCategoryField('collection');

$mapper->addConfigurableField('color');
$mapper->addConfigurableField('size');
```

The number of names of attributes which can be used for categories and configurable attributes is unbounded, every successive attribute will add up to previous ones (in the example, both *category* and *collection* will be considered as categories). Specifying the same attribute name more than once has the same effect of specifying it just once. Although not relevant in most use-cases, the same attribute can be specified both as a category *and* a configurable attribute. If no category nor configurable fields are selected, every attribute is treated as a simple attribute with no special meaning.

### Inserting and Updating Products

Once a product is defined and the mapper is instantiated, it is possible to write directly to the database:

```php
$mapper->create($product);
```

Updates can be executed in the same way:

```php
$mapper->update($product);
```

While a creation is guaranteed to work (duplicated products are allowed, hence no warning are produced, nor exceptions thrown), updates may silently fail if no products match the *identifier attribute* of given product. If at least one matching product exist, however, they will be all updated at the same time.

### Deleting Products

To (soft) delete a product it is sufficient to pass its CMS identifier to the delete method of the mapper:

```php
$mapper->delete('P-001');
```

every product sharing the same identifier will be marked for soft deletion. Users are not allowed to perform hard deletion.

### Final Notes

Before attempting to change the collector database state, the handshaking phase must be completed as described in the *AccelaSearch - Custom Integration* document.

Users are strongly encouraged to use database transactions when writing data. The following code is paradigmatic for most use-cases:

```php
$data = read_product_data_from_cms_as_array();
$products = array_map('your-function-to-convert-from-cms-format-to-ADT', $data);
$credential = ItemMapper::getCredentialFromApiKey('my-api-key');
$dbh = new \PDO(
    'mysql:dbname=' . $credential['name'] . ';host=' . $credential['hostname'],
    $credendial['username'],
    $credendial['password']
);
$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$mapper = new ItemMapper($dbh, 42, 'sku');

acquire_collector_lock('my-api-key', 42);
$dbh->beginTransaction();
try {
    foreach ($products as $product) {
        $mapper->create($product);
    }
    $dbh->commit();
} catch (\Exception $e) {
    $dbh->rollBack();
}
release_collector_lock('my-api-key', 42);
```

### Other Formats

The Product Mapper can read/write to JSON using the same format deployed by AccelaSearch for products when returning search results (see *AccelaSearch - Custom Integration* for details), as well as the PHP dictionary format, which is the equivalent form based on PHP arrays:

```php
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Stock as StockDictionary;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Price as PriceDictionary;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Dictionary\Item as ItemDictionary;
use \Ittweb\AccelaSearch\ProductMapper\Mapper\Json\DictionaryToJsonAdapter as ItemJson;

$dictionary_mapper = new ItemDictionary(new StockDictionary(), new PriceDictionary());
$json_mapper = new ItemJson($dictionary_mapper);
```

