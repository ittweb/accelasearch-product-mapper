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

## Overview

After registering to AccelaSearch, the system will release an **API key**, such as `my-api-key`, which should be kept secret and stored securely. API key may be used to instantiate a `DataMapper\Api\Client` object, which allows to retrieve information about supported CMSs and collector. The former should be used to create an instance of `Shop`, while the latter should be used to establish an SQL connection towards the collector, which may be used to store information about shops and products. In order to facilitate these operations, AccelaSearch - Product Mapper offers a number of utilities and facades, which represent the preferred way to interact with the collector.

## Retrieving Information

Basic information, such as list of supported CMSs and collector credentials, may be accessed through AccelaSearch's API system by using the `DataMapper\Api\Client` along with the relative data mappers.

```php
use \AccelaSearch\ProductMapper\DataMapper\Api\Client;
use \AccelaSearch\ProductMapper\DataMapper\Api\Cms as CmsMapper;
use \AccelaSearch\ProductMapper\DataMapper\Api\Collector as CollectorMapper;

$client = Client::fromApiKey("my-api-key");
$cms_mapper = new CmsMapper($client);
$collector_mapper = new CollectorMapper($client);

$cms_list = $cms_mapper->search();
$collector = $collector_mapper->read();
```

## Connecting to the Collector

Once a collector object is obtained (which is likely to happen through `DataMapper\Api\Collector`), it can be used to establish a connection to the collector database:

```php
use \PDO;

$dbh = new PDO(
    'mysql:host=' . $collector->getHostName() . ';dbname=' . $collector->getDatabaseName(),
    $collector->getUsername(),
    $collector->getPassword(),
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
```

## Managing Shops

An SQL connection may be used to instantiate a shop data mapper, which may in turn be used to insert instances of `Shop` into the collector:

```php
use \AccelaSearch\ProductMapper\Shop;
use \AccelaSearch\ProductMapper\DataMapper\Sql\Shop as ShopMapper;

// $cms_list = ...
// $dbh = ...
$shop_mapper = ShopMapper::fromConnection($dbh);
$shop = new Shop("http://www.shop.com", "en", $cms_list[0]);
$shop_mapper->create($shop);
```

a unique shop identifier will be created upon insertion, and will be assigned to `$shop`. Such identifier may be used to retrieve the shop from the database and update its information or soft-delete it:

```php
// Shop has identifier 2
$shop = $shop_mapper->read(2);
$shop->setUrl("http://www.new-url.com");
$shop_mapper->update($shop);

// Soft-deletion
$shop->setIsActive(false);
$shop_mapper->update($shop);
```

**Note**: shops should not be hard-deleted manually, AccelaSearch will periodically scan the collector and take appropriate actions, eventually removing soft-deleted shops.

## Managing Items

The preferred way to handle item data is through the `CollectorFacade`, which automatically handles SQL transactions and rollbacks, insertion of relational features, implicit handling of insert-versus-update. The `CollectorFacade` requires an instance of `DataMapper\API\Client` and the identifier of the shop on which operate, it will retrieve all of the necessary connection information automatically:

```php
use \AccelaSearch\ProductMapper\DataMapper\Api\Client;
use \AccelaSearch\ProductMapper\CollectorFacade;

$client = Client::fromApiKey("my-api-key");
$collector = new CollectorFacade($client, 2);
```

`CollectorFacade` offers four methods to interact with products: `load`, `searchByExternalIdentifier`, `save` and `delete`. The latter will perform a soft delete, while `save` will either perform an insertion or update depending on the presence of the item in the collector:

```php
// Retrieves item with identifier 42
$item = $collector->load(42);

// Retrieves item having external identifier "ITM0001"
$item = $collector->searchByExternalIdentifier("ITM0001");

// Updates item information
$item->setUrl("http://new-shop-url.com/ITM0001");
$collector->save($item);

// Soft deletion
$collector->delete($item);
```

