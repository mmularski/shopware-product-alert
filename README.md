# Product Alert extension

This extension provides a functionality of "Out of stock notification".

If product is out of stock, a user can sign to product alert notification which sends an email when this product is back to stock


## Table of content

* [Documentation](#documentation)
* [Setup](#setup)
* [Tests](#tests)

## Documentation

### Endpoints

**/sales-channel-api/v{version}/product/alert/sign**

Resolves a given path to cms or product page and accepts include parameters to specifiy the fields contained in your response.

## Setup

### Install plugin

Clone the repository into the `custom/plugins` directory within your Shopware installation. And run the following commands in your Shopware root directory.

Refresh plugin list

```bash
$ bin/console plugin:refresh
```

Install and activate the plugin

```bash
$ bin/console plugin:install --activate ProductAlert
```

Clear the cache (sometimes invalidation is needed for the new routes to activate)

```bash
$ bin/console cache:clear
```
    
## Tests

Tests are located in `src/Test` and configured in `phpunit.xml`.

In order to run the tests you have to set up the test database so that Shopware runs them with our plugin enabled.

After the plugin is installed in your shop, make sure you execute the follwing command (in the Shopware root directory) to dump the current configuration of your shop to the test-database (when using Docker, run it inside the container):

```bash
$ ./psh.phar init-test-databases
```

Then execute the following commands in the plugin's root directory to run the test.

```bash
$ composer install
$ ../../../vendor/bin/phpunit
```
