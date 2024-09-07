<?php
require_once("Models/Database.php");

$dbContext = new DBContext();

$sortOrder = $_GET['sortOrder'] ?? "";
$sortCol = $_GET['sortCol'] ?? "";
$searchQuery = $_GET['searchedProduct'] ?? "";
$selectedCategory = $_GET['selectedCategory'] ?? "";


function getCSVfromAPI() {

$ch = curl_init();
$url = "https://electronics-api.vercel.app/products";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    
    echo 'Error:' . curl_error($ch);
} else {
    header("Content-Type: text/csv");

    $data = json_decode($response, true);

    foreach($data as $row) {
        echo ($row["id"].",".$row["title"]."\n");
    }
}

curl_close($ch);
}

$allProducts = $dbContext->getAllProducts($sortCol, $sortOrder, $searchQuery);

function getCsv($allProducts) {
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=products.csv");

$handle = fopen("php://output", "w");
$header_arguments = array("title", "price", "popularity", "stockLevel", "categoryId");

fputcsv ($handle, $header_arguments);

foreach ($allProducts as $product) {
    fputcsv ($handle, (array) $product);
}
fclose($handle);
exit();
}

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
    if (isset ($_POST["csvButton"])) {
        getCsv($dbContext->getAllProducts($sortCol, $sortOrder));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Homepage - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/styles.css" rel="stylesheet" />
</head>
<body>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">SuperShoppen<?php if ($selectedCategory) echo " - $selectedCategory"; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          
                <li class="nav-item"><a class="nav-link" href="#!">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">Create account</a></li>
                <li class="nav-item"><a class="nav-link" href="newproduct.php">Add new product</a></li>

                <form method="POST" action="">
                    <input type="submit" name="csvButton" value="Download as csv" class="csv">
                </form>

            </ul>
            <form class="d-flex" method="GET" action="">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchedProduct" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <input type="hidden" name="selectedCategory" value="<?php echo urlencode($selectedCategory); ?>">
                <button class="btn btn-outline-dark" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<!-- Section-->

<section class="py-5">


    <div class="container px-4 px-lg-5 mt-5">
        <table class="table">
            <thead>
            <tr>
<th>
    <a class="sorting-button" href="?sortCol=title&sortOrder=<?php echo ($sortCol === 'title' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>&searchedProduct=<?php echo urlencode($searchQuery); ?>">
        Name
        <?php if ($sortCol === 'title') { ?>
            <span class="bi bi-arrow-<?php echo ($sortOrder === 'asc') ? 'up' : 'down'; ?>"></span>
        <?php } ?>
    </a>
</th>
<th>
    <a class="sorting-button" href="?sortCol=categoryId&sortOrder=<?php echo ($sortCol === 'categoryId' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>&searchedProduct=<?php echo urlencode($searchQuery); ?>">
        Category
        <?php if ($sortCol === 'categoryId') { ?>
            <span class="bi bi-arrow-<?php echo ($sortOrder === 'asc') ? 'up' : 'down'; ?>"></span>
        <?php } ?>
    </a>
</th>
<th>
    <a class="sorting-button" href="?sortCol=price&sortOrder=<?php echo ($sortCol === 'price' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>&searchedProduct=<?php echo urlencode($searchQuery); ?>">
        Price
        <?php if ($sortCol === 'price') { ?>
            <span class="bi bi-arrow-<?php echo ($sortOrder === 'asc') ? 'up' : 'down'; ?>"></span>
        <?php } ?>
    </a>
</th>
<th>
    <a class="sorting-button" href="?sortCol=stockLevel&sortOrder=<?php echo ($sortCol === 'stockLevel' && $sortOrder === 'asc') ? 'desc' : 'asc'; ?>&selectedCategory=<?php echo urlencode($selectedCategory); ?>&searchedProduct=<?php echo urlencode($searchQuery); ?>">
        Stock Level
        <?php if ($sortCol === 'stockLevel') { ?>
            <span class="bi bi-arrow-<?php echo ($sortOrder === 'asc') ? 'up' : 'down'; ?>"></span>
        <?php } ?>
    </a>
</th>
            </tr>
            </thead>
            <tbody>
    <?php
        if (!empty($allProducts)) {
            foreach ($allProducts as $product) {
                echo "<tr class='product-row' data-product-id='{$product['id']}'>";
                echo "<td>{$product['title']}</td>";
                echo "<td>{$product['categoryId']}</td>";
                echo "<td>{$product['price']}</td>";
                echo "<td>{$product['stockLevel']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No products available.</td></tr>";
        }
        ?>

    </tbody>
        </table>
    </div>
</section>

<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website <?php echo date('Y'); ?></p></div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script>
    //Row click nav
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                const productId = row.getAttribute('data-product-id');
                if (productId) {
                    window.location.href = `product.php?id=${productId}`;
                }
            });
        }); 
    });
</script>
</body>
</html>