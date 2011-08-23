# Simple PHP cURL Library

## Overview

Simple PHP cURL library. Supports requests for GET, POST, PUT, PATCH and DELETE.

## Requirements

PHP 5.3+
cURL

## Usage

```php
use Network\Curl\Curl;

$curl = new Curl();

$response = $curl->get("https://api.github.com/users/dsyph3r");

$curlInfo   = $response['curl_info'];
$status     = $response['status'];
$headers    = $response['headers'];
$data       = json_decode($response['data'], true);
```

## Examples

There are some simple examples using Google Geocoding API and GitHub API in the
examples folder

## Tests

Library is tested with PHPUnit

Run tests with

```bash
$ phpunit
```

