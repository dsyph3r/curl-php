<?php

require_once dirname(__FILE__) . '/../lib/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

/**
 * Configure the autoloader
 *
 * The Symfony ClassLoader Component is used, but could easy be substituted for
 * another autoloader.
 *
 * @link https://github.com/symfony/ClassLoader
 * @link http://symfony.com/doc/current/cookbook/tools/autoloader.html
 */
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
// Register the location of the GitHub namespace
$loader->registerNamespaces(array(
  'Network'           => __DIR__.'/../lib'
));
$loader->register();


use Network\Curl\Curl;

$curl = new Curl();

/**
 * Google Geocoding API example
 *
 * @link http://code.google.com/apis/maps/documentation/geocoding/
 */
echo "Geolocating ...\n";
$address = 'Millenium Stadium, Cardiff, Wales';
$response = $curl->get('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');

$status     = $response['status'];
$headers    = $response['headers'];
$data       = json_decode($response['data'], true);

echo "\tStatus:\t\t$status\n";
echo "\tHeaders:\t" . print_r($headers, true) . "\n";
echo "\tLatLng:\t\t" . $data['results'][0]['geometry']['location']['lat'] . ',' . $data['results'][0]['geometry']['location']['lat'] . "\n";
echo "\n\n";


/**
 * GitHub API example
 *
 * @link http://developer.github.com/v3/users/
 */
echo "GitHub ...\n";
$response = $curl->get('https://api.github.com/users/dsyph3r');

$status     = $response['status'];
$headers    = $response['headers'];
$data       = json_decode($response['data'], true);

echo "\tStatus:\t\t$status\n";
echo "\tHeaders:\t" . print_r($headers, true) . "\n";
echo "\tUrl:\t\t" . $data['html_url']. "\n";
