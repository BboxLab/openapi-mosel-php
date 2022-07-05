# Moselle, an sdk package for BT open APIs

This package is still under construction (july 4h 2022).

Open Api are described on the Bouygues Telecom Developer Portal: https://developer.bouyguestelecom.fr

## Install

```
composer require bboxlab/moselle
```

## List of API and tools in Open API SDK

- authenticator for app credentials Oauth flow
- check email address

## How to use?

First, Install the package on a php application with composer.

Then, create an Sdk Moselle Object with a configuration.

```php
$configuration = new Configuration();
...
$sdk = new Sdk('clientId', 'secretId', $configuration);

```

You can use a preexisting configuration, here for the default bt env configuration.

```php
$sdk = new Sdk('clientId', 'secretId', new ConfigurationCreator()->createApConfig());
```

When sdk is set correctly, you can use it to fetch secured open api

```php
$response = $sdk->checkEmail('example@email.com')
```

Response object returns the app credentials token created and the response given by Bt Api in array. 

## How to test Moselle package

You can use phpunit to launch the tests:

```bash
 ./vendor/bin/phpunit tests/
```
