<?php
include '../includes/header.php';
include '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $storeId = $_POST['store_id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("INSERT INTO menu_items (store_id, name, category, price, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$storeId, $name, $category, $price, $stock]);
}

$stmt = $pdo->query("SELECT * FROM menu_items");
$items = $stmt->fetchAll();

$stmt = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id WHERE o.status != 'pending'");
$orders = $stmt->fetchAll();
?>

<h2>Admin Dashboard</h2>
<div class="profile-section">
    <h3>Add Menu Item</h3>
    <form method="POST">
        <label>Store:</label>
        <select name="store_id">
            <?php
            $stmt = $pdo->query("SELECT * FROM stores");
            while ($store = $stmt->fetch()) {
                echo "<option value='{$store['id']}'>{$store['name']}</option>";
            }
            ?>
        </select><br>
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Category: <select name="category"><option value="main">Main</option><option value="sides">Sides</option><option value="desserts">Desserts</option></select></label><br>
        <label>Price: <input type="number" name="price" step="0.01" required></label><br>
        <label>Stock: <input type="number" name="stock" required></label><br>
        <button type="submit" name="add_item">Add Item</button>
    </form>
</div>

<div class="profile-section">
    <h3>Menu Items</h3>
    <?php foreach ($items as $item): ?>
        <p><?php echo $item['name']; ?> - ৳<?php echo $item['price']; ?> (Stock: <?php echo $item['stock']; ?>)</p>
    <?php endforeach; ?>
</div>

<div class="profile-section">
    <h3>Live Orders</h3>
    <?php foreach ($orders as $order): ?>
        <p>Order #<?php echo $order['id']; ?> by <?php echo $order['username']; ?> - Status: <?php echo $order['status']; ?></p>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>