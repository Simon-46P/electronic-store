<?php
require_once("vendor/autoload.php");

class DBContext {
  private $apiUrl = "https://electronics-api.vercel.app";

    private function fetchAllProductsFromAPI() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}/products");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);
        $allProducts = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die('JSON decoding error: ' . json_last_error_msg());
        }
        return $allProducts;
    }

    function getAllProducts($sortCol = null, $sortOrder = null, $searchQuery = null)
    {
        $products = $this->fetchAllProductsFromAPI();
        if ($searchQuery) {
            $products = array_filter($products, function($product) use ($searchQuery) {
                return stripos($product['title'], $searchQuery) !== false;
            });
        }
        if ($sortCol && $sortOrder) {
            usort($products, function($a, $b) use ($sortCol, $sortOrder) {
                if (!isset($a[$sortCol]) || !isset($b[$sortCol])) {
                    return 0;
                }
                if ($a[$sortCol] == $b[$sortCol]) {
                    return 0;
                }
                if ($sortOrder == 'asc') {
                    return ($a[$sortCol] < $b[$sortCol]) ? -1 : 1;
                } else {
                    return ($a[$sortCol] > $b[$sortCol]) ? -1 : 1;
                }
            });
        }

        return $products;
    }
 
   function getProduct($id) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}/product/{$id}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);
    $product = json_decode($response, true); // true returns an associative array

    if (json_last_error() !== JSON_ERROR_NONE) {
        die('JSON decoding error: ' . json_last_error_msg());
    }

    // Convert the array to an object if needed
    if (is_array($product)) {
        $product = (object) $product;
    }

    return $product;
}
}