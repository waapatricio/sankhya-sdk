<div align="center">

# The Sankhya PHP SDK

A PHP package that helps kickstart your next [Sankhya Api](https://sankhya.com.br) integration.

![GitHub all releases](https://img.shields.io/github/downloads/waapatricio/sankhya-sdk/total)
![X (formerly Twitter) Follow](https://img.shields.io/twitter/follow/waapatricio)


[Documentation](https://developer.sankhya.com.br/reference/api-de-integra%C3%A7%C3%B5es-sankhya)

</div>

## Introduction

The Sankhya PHP SDK is a powerful tool that enables seamless integration with the Sankhya platform, allowing you to interact with Sankhya data programmatically.

```php
<?php

$sankhya = new Sankhya('app-key', 'app-token', 'user', 'password');

$response = $sankhya->products()->all();

$data = $response->dto(); // Available in json, xml, object, collection
```

## Getting Started

You can install the package using [Composer](https://getcomposer.org):

```shell
composer require waapatricio/sankhya-php-sdk
```
## Documentation

Visit our documentation [here](https://developer.sankhya.com.br/reference/api-de-integra%C3%A7%C3%B5es-sankhya) to get started.
