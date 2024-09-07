<?php
require_once("vendor/autoload.php");

class DBContext {
  private $apiUrl = "http://localhost:3000";

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
//GET
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
 //GET (SINGLE)
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
    $product = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die('JSON decoding error: ' . json_last_error_msg());
    }

    if (is_array($product)) {
        $product = (object) $product;
    }

    return $product;
}
//POST
function addProduct($productData) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}/products");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die('cURL error: ' . curl_error($ch));
    }

    curl_close($ch);

    $decodedResponse = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die('JSON decoding error: ' . json_last_error_msg());
    }

    return $decodedResponse;
}


//DELETE
   function deleteProduct($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}/product/{$id}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die('JSON decoding error: ' . json_last_error_msg());
        }

        return $decodedResponse;
    }

//PUT
        function updateProduct($id, $updatedData) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}/product/{$id}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updatedData));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'JSON decoding error: ' . json_last_error_msg()];
        }

        return $decodedResponse;
    }
}

