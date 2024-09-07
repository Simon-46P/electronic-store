<?php
require_once('Models/Database.php');

$dbContext = new DBContext();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    ob_clean();

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['title']) || !isset($input['price']) || !isset($input['popularity']) || !isset($input['stockLevel']) || !isset($input['categoryId'])) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    $productData = [
        'title' => htmlspecialchars($input['title']),
        'price' => floatval($input['price']),
        'popularity' => intval($input['popularity']),
        'stockLevel' => intval($input['stockLevel']),
        'categoryId' => htmlspecialchars($input['categoryId'])
    ];


    $response = $dbContext->addProduct($productData);

    if ($response['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Product successfully added.',
            'product' => $response['product']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $response['message'] ?? 'Unknown error']);
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link href="/css/styles.css" rel="stylesheet" />
</head>
<body>
    <div class="container py-4">
        <h1>Add New Product</h1>
        <div class="card p-4">
            <form id="productForm">
                <label for="title">Title:</label>
                <input type="text" id="title" required>

                <label for="price">Price:</label>
                <input type="number" id="price" step="0.01" required>

                <label for="popularity">Popularity:</label>
                <input type="number" id="popularity" required>

                <label for="stockLevel">Stock Level:</label>
                <input type="number" id="stockLevel" required>

                <label for="categoryId">Category ID:</label>
                <input type="text" id="categoryId" required>

                <button type="submit">Add</button>
            </form>
            <a class="btn btn-primary mt-3" href="/">Back to Home</a>
        </div>
    </div>

<script>
document.getElementById('productForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const productData = {
        title: document.getElementById('title').value,
        price: parseFloat(document.getElementById('price').value),
        popularity: parseInt(document.getElementById('popularity').value, 10),
        stockLevel: parseInt(document.getElementById('stockLevel').value, 10),
        categoryId: document.getElementById('categoryId').value
    };

    fetch('http://localhost:3000/products', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(productData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Product successfully added.');
            window.location.href = '/';
        } else {
            alert('Failed to add the product: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the product.');
    });
});
</script>

</body>
</html>
