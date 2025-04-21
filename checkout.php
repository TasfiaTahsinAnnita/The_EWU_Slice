<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$storeId = $_SESSION['store_id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM delivery_zones WHERE store_id = ?");
$stmt->execute([$storeId]);
$zones = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $zoneId = $_POST['delivery_zone'];

    $stmt = $pdo->prepare("SELECT min_order_amount, delivery_fee FROM delivery_zones WHERE id = ?");
    $stmt->execute([$zoneId]);
    $zone = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT total_amount FROM orders WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$userId]);
    $total = $stmt->fetchColumn() ?: 0;

    if ($total < $zone['min_order_amount']) {
        echo "<p>Order total must be at least ৳{$zone['min_order_amount']} for this delivery zone.</p>";
    } else {
        $total += $zone['delivery_fee'];
        $stmt = $pdo->prepare("UPDATE orders SET status = 'preparing', total_amount = ? WHERE user_id = ? AND status = 'pending'");
        $stmt->execute([$total, $userId]);
        header("Location: order_tracking.php");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT total_amount FROM orders WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$userId]);
$total = $stmt->fetchColumn() ?: 0;
?>

<h2>Checkout</h2>
<p>Total: ৳<?php echo number_format($total, 2); ?></p>
<form method="POST">
    <label>Delivery Zone:</label>
    <select name="delivery_zone" required>
        <?php foreach ($zones as $zone): ?>
            <option value="<?php echo $zone['id']; ?>">
                <?php echo $zone['zone_name']; ?> (Min: ৳<?php echo $zone['min_order_amount']; ?>, Fee: ৳<?php echo $zone['delivery_fee']; ?>)
            </option>
        <?php endforeach; ?>
    </select><br>
    <label>Address: <input type="text" name="address" required></label><br>
    <button type="submit">Place Order</button>
</form>
<?php include 'includes/footer.php'; ?>