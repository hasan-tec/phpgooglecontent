<?php
require_once 'vendor/autoload.php';

use Google\Client;
use Google\Service\ShoppingContent;

// Path to the service account key file
$keyFilePath = 'service-info.json';

$client = new Client();
$client->setAuthConfig($keyFilePath);
$client->addScope('https://www.googleapis.com/auth/content');

// Initialize the Content API service
$service = new ShoppingContent($client);

// Your Merchant ID
$merchantId = '5390490385';

try {
    // Retrieve the list of products
    $parameters = array();
    $products = $service->products->listProducts($merchantId, $parameters);

    while (!empty($products->getResources())) {
        foreach ($products->getResources() as $product) {
            printf("%s %s\n", $product->getId(), $product->getTitle());
        }
        if (!$products->getNextPageToken()) {
            break;
        }
        $parameters['pageToken'] = $products->getNextPageToken();
        $products = $service->products->listProducts($merchantId, $parameters);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>