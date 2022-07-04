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

- Install the package on a php application with composer
- Call the Helper you want to use for calling a Bt Open API: for example EmailChecker for check email address
  - pass credentials in the call or a token if you already have one
  - you can get the token from the helper response and keep it in a storage for multiple open api calls
  - get the reponse content for checking the email address

## How to test Moselle package

You can use phpunit to launch the tests:

```bash
 ./vendor/bin/phpunit tests/
```
