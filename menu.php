<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (!isset($_SESSION['store_id']) && isset($_POST['store_id'])) {
    $_SESSION['store_id'] = $_POST['store_id'];
}

$storeId = $_SESSION['store_id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE store_id = ? AND stock > 0");
$stmt->execute([$storeId]);
$items = $stmt->fetchAll();
?>

<h2>Menu</h2>
<div class="filter-buttons">
    <button class="filter-btn active" data-category="all">All</button>
    <button class="filter-btn" data-category="main">Main</button>
    <button class="filter-btn" data-category="sides">Sides</button>
    <button class="filter-btn" data-category="desserts">Desserts</button>
</div>

<?php foreach ($items as $item): ?>
    <div class="menu-item" data-category="<?php echo $item['category']; ?>">
        <div>
            <h3><?php echo $item['name']; ?> (<?php echo ucfirst($item['category']); ?>)</h3>
            <p>Price: ৳<?php echo $item['price']; ?></p>
        </div>
        <button onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo $item['name']; ?>', <?php echo $item['price']; ?>)">
            Add to Cart
        </button>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>