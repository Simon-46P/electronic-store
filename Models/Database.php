<?php
require_once('Models/Product.php');
require_once('Models/Category.php');
require_once("vendor/autoload.php");

class DBContext{

    private $pdo;
    
    function __construct() {  
        
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $host = $_ENV['host'];
        $db   = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];
        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->initIfNotInitialized();
        $this->seedfNotSeeded();
    }

    function getAllCategories(){
        return $this->pdo->query('SELECT * FROM category')->fetchAll(PDO::FETCH_CLASS, 'Category');
        
    }
    



function getAllProducts($sortCol = null, $sortOrder = null, $searchQuery = null)
{
    $sql = "SELECT * FROM products";

    if ($searchQuery) {
        $sql .= " WHERE title LIKE ?";
        $params = ["%$searchQuery%"];
    } else {
        $params = [];
    }

    if ($sortCol && $sortOrder) {
        $sql .= " ORDER BY $sortCol $sortOrder";
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
}

    function getProduct($id){
        $prep = $this->pdo->prepare('SELECT * FROM products where id=:id');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Product');
        $prep->execute(['id'=> $id]);
        return  $prep->fetch();
    }
function getProductByTitle($title, $sortCol = null, $sortOrder = null)
{
    $sql = "SELECT * FROM products WHERE title LIKE ?";
    $params = ["%$title%"];

    if ($sortCol && $sortOrder) {
        $sql .= " ORDER BY $sortCol $sortOrder";
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
}

    function getCategoryByTitle($title) : Category | false{
        $prep = $this->pdo->prepare('SELECT * FROM category where title=:title');
        $prep->setFetchMode(PDO::FETCH_CLASS,'Category');
        $prep->execute(['title'=> $title]); 
        return  $prep->fetch();
    }

function getProductsByCategory($categoryTitle, $sortCol = null, $sortOrder = null, $searchQuery = null)
{
    if ($sortCol == null) {
        $sortCol = "Id";
    }
    if ($sortOrder == null) {
        $sortOrder = "asc";
    }

    $category = $this->getCategoryByTitle($categoryTitle);
    if (!$category) {
        return [];
    }

    $sql = "SELECT * FROM products WHERE categoryId = :categoryId";

    if ($searchQuery) {
        $sql .= " AND title LIKE '%" . $searchQuery . "%'";
    }

    $sql .= " ORDER BY $sortCol $sortOrder";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':categoryId', $category->id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Product');
}

     function getPopularProducts($limit = 10) {
        $query = "SELECT * FROM products ORDER BY popularity DESC LIMIT :limit";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    function seedfNotSeeded(){
        static $seeded = false;
        if($seeded) return;
$this->createIfNotExisting('GizmoFlex Z2 Pro', 899, 75, 40, 'Phones');
$this->createIfNotExisting('Infinity Max X', 999, 80, 30, 'Phones');
$this->createIfNotExisting('TechPro X1 Smartphone', 799, 85, 50, 'Phones');
$this->createIfNotExisting('NexGen Slim 8', 599, 85, 70, 'Phones');
$this->createIfNotExisting('EcoPlus 5G Ultra', 699, 90, 60, 'Phones');
$this->createIfNotExisting('DynamicPulse 7', 499, 80, 65, 'Phones');
$this->createIfNotExisting('PrimeX 10', 1199, 75, 45, 'Phones');
$this->createIfNotExisting('EcoTech 4G Lite', 399, 70, 75, 'Phones');
$this->createIfNotExisting('MaxLink X3', 449, 85, 55, 'Phones');
$this->createIfNotExisting('SmartWave 6', 749, 88, 60, 'Phones');
$this->createIfNotExisting('SwiftBook Pro 15 Laptop', 1299, 85, 30, 'Laptops');
$this->createIfNotExisting('MaxSpeed Studio Pro', 1399, 90, 25, 'Laptops');
$this->createIfNotExisting('UltraBook X2 Convertible', 1099, 70, 40, 'Laptops');
$this->createIfNotExisting('ZenAir 14 Ultra', 999, 75, 50, 'Laptops');
$this->createIfNotExisting('EcoTech X1 Gaming Laptop', 1499, 80, 20, 'Laptops');
$this->createIfNotExisting('ProFusion 17', 1699, 88, 35, 'Laptops');
$this->createIfNotExisting('SwiftFlex 13', 899, 78, 55, 'Laptops');
$this->createIfNotExisting('GigaBook 15', 1199, 82, 45, 'Laptops');
$this->createIfNotExisting('UltraVision X', 1599, 72, 40, 'Laptops');
$this->createIfNotExisting('ZenBook X3', 1099, 90, 30, 'Laptops');
$this->createIfNotExisting('Audiophile Pro-500 Headphones', 199, 95, 100, 'Headphones');
$this->createIfNotExisting('DynamicX Sports Earbuds', 79, 90, 200, 'Headphones');
$this->createIfNotExisting('ZenBeats Over-Ear', 149, 80, 120, 'Headphones');
$this->createIfNotExisting('MegaBass Studio Series', 249, 70, 80, 'Headphones');
$this->createIfNotExisting('RhythmBliss True Wireless', 129, 85, 150, 'Headphones');
$this->createIfNotExisting('SoundWave Pro', 179, 88, 130, 'Headphones');
$this->createIfNotExisting('BassFusion Elite', 99, 75, 180, 'Headphones');
$this->createIfNotExisting('EcoTunes 5G', 69, 82, 220, 'Headphones');
$this->createIfNotExisting('FlexTone Wireless', 119, 78, 160, 'Headphones');
$this->createIfNotExisting('EchoBlast Soundbar', 199, 90, 120, 'Headphones');
$this->createIfNotExisting('CinemaPro OLED 65"', 1999, 90, 15, 'Televisions');
$this->createIfNotExisting('UltraVision 4K Smart TV', 1499, 85, 20, 'Televisions');
$this->createIfNotExisting('EcoView 50" QLED', 999, 75, 40, 'Televisions');
$this->createIfNotExisting('QuantumX HDR 55"', 1299, 80, 30, 'Televisions');
$this->createIfNotExisting('GigaVision LED 43"', 599, 70, 50, 'Televisions');
$this->createIfNotExisting('SmartEdge 55"', 899, 78, 35, 'Televisions');
$this->createIfNotExisting('FlexView Ultra HD', 1199, 85, 25, 'Televisions');
$this->createIfNotExisting('DynamicVision 60"', 799, 72, 45, 'Televisions');
$this->createIfNotExisting('EcoTech 4K 50"', 899, 75, 40, 'Televisions');
$this->createIfNotExisting('PrimeView 65" QLED', 1599, 88, 20, 'Televisions');
$this->createIfNotExisting('SwiftSync Thunderbolt Dock', 199, 80, 80, 'Accessories');
$this->createIfNotExisting('RapidSync USB-C Cable', 19, 90, 300, 'Accessories');
$this->createIfNotExisting('GamerZone RGB Mechanical Keyboard', 149, 70, 50, 'Accessories');
$this->createIfNotExisting('CyberShield Webcam Cover', 9, 95, 500, 'Accessories');
$this->createIfNotExisting('PowerCore Solar Charger', 129, 85, 100, 'Accessories');
$this->createIfNotExisting('SmartFlex Wireless Charger', 39, 88, 200, 'Accessories');
$this->createIfNotExisting('GigaHub USB Hub', 29, 75, 250, 'Accessories');
$this->createIfNotExisting('SoundFlex Portable Speaker', 59, 82, 180, 'Accessories');
$this->createIfNotExisting('EcoLight LED Strip', 49, 78, 220, 'Accessories');
$this->createIfNotExisting('UltraCharge Power Adapter', 79, 90, 150, 'Accessories');
$this->createIfNotExisting('PulseFit Fitness Tracker', 79, 90, 100, 'Wearables');
$this->createIfNotExisting('HealthMax Pro ECG Monitor', 199, 75, 60, 'Wearables');
$this->createIfNotExisting('GyroFlex Activity Ring', 59, 80, 150, 'Wearables');
$this->createIfNotExisting('EcoBand Smartwatch', 129, 85, 80, 'Wearables');
$this->createIfNotExisting('AirPulse Wireless Earbuds', 89, 95, 120, 'Wearables');
$this->createIfNotExisting('FlexFit Sports Watch', 149, 70, 110, 'Wearables');
$this->createIfNotExisting('SoundBeat Fitness Earphones', 49, 88, 140, 'Wearables');
$this->createIfNotExisting('HealthWave Pro Bracelet', 179, 82, 90, 'Wearables');
$this->createIfNotExisting('SmartStep Activity Tracker', 69, 78, 130, 'Wearables');
$this->createIfNotExisting('EcoPulse Smart Ring', 99, 90, 110, 'Wearables');
$this->createIfNotExisting('GigaFlex XR Viewer', 199, 65, 35, 'Virtual Reality');
$this->createIfNotExisting('VRX-800 Pro VR Glasses', 499, 80, 20, 'Virtual Reality');
$this->createIfNotExisting('EcoVision VR Helmet', 399, 75, 25, 'Virtual Reality');
$this->createIfNotExisting('VRX-500 Virtual Reality Headset', 299, 70, 30, 'Virtual Reality');
$this->createIfNotExisting('DynamicView VR Kit', 249, 85, 40, 'Virtual Reality');
$this->createIfNotExisting('SmartEdge AR Glasses', 599, 78, 15, 'Virtual Reality');
$this->createIfNotExisting('FlexReality 3D Viewer', 149, 72, 45, 'Virtual Reality');
$this->createIfNotExisting('VisionX 360 VR System', 899, 88, 10, 'Virtual Reality');
$this->createIfNotExisting('EcoSight XR Goggles', 349, 80, 25, 'Virtual Reality');
$this->createIfNotExisting('UltraView VR Headset', 179, 75, 35, 'Virtual Reality');
$this->createIfNotExisting('EcoTemp Smart Thermostat', 149, 80, 60, 'Smart Home');
$this->createIfNotExisting('ROBO-Clean Robot Vacuum', 299, 75, 30, 'Smart Home');
$this->createIfNotExisting('SmartHome Hub V2', 299, 85, 80, 'Smart Home');
$this->createIfNotExisting('EchoSync Smart Mirror', 399, 70, 10, 'Smart Home');
$this->createIfNotExisting('ZenHome Smart Lights', 59, 82, 70, 'Smart Home');
$this->createIfNotExisting('EcoWave Smart Plug', 29, 88, 120, 'Smart Home');
$this->createIfNotExisting('GigaGuard Security Camera', 79, 75, 90, 'Smart Home');
$this->createIfNotExisting('TechView Doorbell Camera', 149, 78, 60, 'Smart Home');
$this->createIfNotExisting('AuraSense Smart Lock', 199, 80, 50, 'Smart Home');
$this->createIfNotExisting('PrimeSync Smart Switch', 39, 85, 80, 'Smart Home');
$this->createIfNotExisting('ProGamer XL Gaming Chair', 399, 70, 50, 'Gaming Gear');
$this->createIfNotExisting('AuraGlow Gaming Desk', 199, 75, 40, 'Gaming Gear');
$this->createIfNotExisting('GamerZone RGB Gaming Mouse', 59, 85, 150, 'Gaming Gear');
$this->createIfNotExisting('SwiftFire Gaming Console', 299, 80, 20, 'Gaming Gear');
$this->createIfNotExisting('EchoWave Gaming Headset', 79, 88, 120, 'Gaming Gear');
$this->createIfNotExisting('FlexGrip Controller', 49, 72, 200, 'Gaming Gear');
$this->createIfNotExisting('UltraFlex Gaming Pad', 29, 78, 180, 'Gaming Gear');
$this->createIfNotExisting('RapidFire Gaming Keyboard', 99, 90, 100, 'Gaming Gear');
$this->createIfNotExisting('EcoCharge Gaming Battery', 79, 85, 130, 'Gaming Gear');
$this->createIfNotExisting('VibeSync Gaming Glasses', 149, 75, 90, 'Gaming Gear');
$this->createIfNotExisting('AIHome Smart Speaker', 99, 75, 120, 'Audio Equipment');
$this->createIfNotExisting('ZenSound White Noise Machine', 49, 85, 200, 'Audio Equipment');
$this->createIfNotExisting('MusicPro Wireless Microphone', 79, 70, 100, 'Audio Equipment');
$this->createIfNotExisting('MegaBass Bluetooth Speaker', 99, 80, 150, 'Audio Equipment');
$this->createIfNotExisting('DynamicWave Pro Earphones', 49, 88, 180, 'Audio Equipment');
$this->createIfNotExisting('EchoBeat Wireless Speaker', 79, 72, 130, 'Audio Equipment');
$this->createIfNotExisting('SoundFlex Soundbar', 129, 78, 160, 'Audio Equipment');
$this->createIfNotExisting('FlexTone Studio Monitors', 199, 90, 80, 'Audio Equipment');
$this->createIfNotExisting('EcoTunes Sound System', 179, 85, 90, 'Audio Equipment');
$this->createIfNotExisting('VibeWave Wireless Headphones', 149, 75, 100, 'Audio Equipment');
            $seeded = true;

    }

    function createIfNotExisting( $title,$price, $popularity, $stockLevel, $categoryName ){
        $existing = $this->getProductByTitle($title);
        if($existing){
            return;
        };
        return $this->addProduct( $title, $price, $popularity, $stockLevel, $categoryName );

    }

    function addCategory($title){
        $prep = $this->pdo->prepare('INSERT INTO category (title) VALUES(:title )');
        $prep->execute(["title"=>$title]);
        return $this->pdo->lastInsertId();
    }


    function addProduct( $title, $price, $popularity, $stockLevel, $categoryName ){

        $category = $this->getCategoryByTitle($categoryName);
        if($category == false){
            $this->addCategory($categoryName);
            $category = $this->getCategoryByTitle($categoryName);
        }

        $prep = $this->pdo->prepare('INSERT INTO products (title, price, popularity, stockLevel, categoryId) VALUES(:title, :price, :popularity, :stockLevel, :categoryId )');
        $prep->execute(["title"=>$title,"price"=>$price, "popularity"=>$popularity, "stockLevel"=>$stockLevel,"categoryId"=>$category->id]);
        return $this->pdo->lastInsertId();
                   
    }

    function initIfNotInitialized() {

        static $initialized = false;
        if($initialized) return;


        $sql  ="CREATE TABLE IF NOT EXISTS `category` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `title` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
            ) ";

        $this->pdo->exec($sql);

        $sql  ="CREATE TABLE IF NOT EXISTS `products` (
            `id` INT AUTO_INCREMENT NOT NULL,
            `title` varchar(200) NOT NULL,
            `price` INT,
            `popularity` INT,
            `stockLevel` INT,
            `categoryId` INT NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`categoryId`)
                REFERENCES category(id)
            ) ";

        $this->pdo->exec($sql);

        $initialized = true;
    }


}