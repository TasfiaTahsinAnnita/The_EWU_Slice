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

<!-- Add Bootstrap CSS CDN if not already included -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container my-5">
    <h2 class="mb-4 text-center">Admin Dashboard</h2>

    <!-- Add Menu Item Form -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add Menu Item</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Store</label>
                    <select name="store_id" class="form-select" required>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM stores");
                        while ($store = $stmt->fetch()) {
                            echo "<option value='{$store['id']}'>{$store['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="main">Main</option>
                        <option value="sides">Sides</option>
                        <option value="desserts">Desserts</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (৳)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <button type="submit" name="add_item" class="btn btn-success">Add Item</button>
            </form>
        </div>
    </div>

    <!-- Menu Items Section -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Menu Items</h5>
        </div>
        <div class="card-body">
            <?php if (count($items) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($item['name']); ?> (<?php echo ucfirst($item['category']); ?>)
                            <span class="badge bg-primary rounded-pill">৳<?php echo $item['price']; ?> | Stock: <?php echo $item['stock']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No items found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Orders Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Live Orders</h5>
        </div>
        <div class="card-body">
            <?php if (count($orders) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($orders as $order): ?>
                        <li class="list-group-item">
                            <strong>Order #<?php echo $order['id']; ?></strong> by <?php echo htmlspecialchars($order['username']); ?> -
                            <span class="badge bg-secondary"><?php echo ucfirst($order['status']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No live orders found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
