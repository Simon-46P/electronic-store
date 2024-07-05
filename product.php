<?php
require_once('Models/Database.php');

$dbContext = new DBContext();

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
            <title><?php echo "{$product->title} - Product Details"; ?></title>
    <link href="/css/styles.css" rel="stylesheet" />
        </head>
        <body>
            <div class="container py-4">
                <a class="btn btn-primary mb-3" href="javascript:history.go(-1)">Back</a>
                <div class="card p-4">
                    <h1 class="card-title"><?php echo $product->title; ?></h1>
                    <p>ID: <?php echo $product->id; ?></p>
                    <p>Price: <?php echo $product->price; ?></p>
                    <p>Popularity: <?php echo $product->popularity; ?></p>
                    <p>Stock Level: <?php echo $product->stockLevel; ?></p>
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
