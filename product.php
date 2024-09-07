<?php
require_once('Models/Database.php');

$dbContext = new DBContext();

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    ob_clean();

    $productId = htmlspecialchars($_GET['id']);
    $response = $dbContext->deleteProduct($productId);

    if (isset($response['success']) && $response['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $response['message'] ?? 'Unknown error']);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'update' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    ob_clean();

    $productId = htmlspecialchars($_GET['id']);
    $input = json_decode(file_get_contents('php://input'), true);

    $response = $dbContext->updateProduct($productId, $input);

    if (isset($response['success']) && $response['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $response['message'] ?? 'Unknown error']);
    }
    exit;
}

if (isset($_GET['id'])) {
    $productId = htmlspecialchars($_GET['id']);
    $product = $dbContext->getProduct($productId);

    if ($product) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars("{$product->title} - Product Details"); ?></title>
            <link href="/css/styles.css" rel="stylesheet" />
            <script>
                function deleteProduct(id) {
                    if (confirm("Are you sure you want to delete this product?")) {
                        fetch(`?action=delete&id=${id}`, {
                            method: 'DELETE'
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('Product deleted successfully.');
                                window.location.href = '/';
                            } else {
                                alert('Failed to delete the product: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the product.');
                        });
                    }
                }

                function enableEditing() {
                    const fields = document.querySelectorAll('.editable');
                    fields.forEach(field => {
                        field.disabled = false;
                    });
                    document.getElementById('editButton').style.display = 'none';
                    document.getElementById('saveButton').style.display = 'inline-block';
                }

                function saveProduct(id) {
                    const title = document.getElementById('title').value;
                    const price = parseFloat(document.getElementById('price').value);
                    const popularity = parseInt(document.getElementById('popularity').value);
                    const stockLevel = parseInt(document.getElementById('stockLevel').value);
                    const categoryId = document.getElementById('categoryId').value;

                    const updatedData = {
                        title, 
                        price, 
                        popularity, 
                        stockLevel,
                        categoryId 
                    };

                    fetch(`?action=update&id=${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(updatedData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Product updated successfully.');
                            location.reload();
                        } else {
                            alert('Failed to update the product: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the product.');
                    });
                }
            </script>
        </head>
        <body>
            <div class="container py-4">
                <a class="btn btn-primary mb-3" href="javascript:history.go(-1)">Back</a>
                <div class="card p-4">
                    <h1 class="card-title">
                        <input type="text" id="title" class="editable" value="<?php echo htmlspecialchars($product->title); ?>" disabled />
                    </h1>
                    <p>ID: <?php echo htmlspecialchars($product->id); ?></p>
                    <p>
                        Price: 
                        <input type="text" id="price" class="editable" value="<?php echo htmlspecialchars($product->price); ?>" disabled />
                    </p>
                    <p>
                        Popularity: 
                        <input type="text" id="popularity" class="editable" value="<?php echo htmlspecialchars($product->popularity); ?>" disabled />
                    </p>
                    <p>
                        Stock Level: 
                        <input type="text" id="stockLevel" class="editable" value="<?php echo htmlspecialchars($product->stockLevel); ?>" disabled />
                    </p>
                    <p>
                        Category ID: 
                        <input type="text" id="categoryId" class="editable" value="<?php echo htmlspecialchars($product->categoryId ?? ''); ?>" disabled />
                    </p>
                    <button onclick="deleteProduct(<?php echo htmlspecialchars($product->id); ?>)">Delete</button>
                    <button id="editButton" onclick="enableEditing()">Edit</button>
                    <button id="saveButton" onclick="saveProduct(<?php echo htmlspecialchars($product->id); ?>)" style="display: none;">Save</button>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>No product ID specified.</p>";
}
?>
