<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status != 'pending' ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$userId]);
$order = $stmt->fetch();
?>

<h2>Order Tracking</h2>
<?php if ($order): ?>
    <div class="tracker">
        <h3>Domino's Tracker® <a href="#" onclick="event.preventDefault();">Click here to track</a></h3>
        <div class="tracker-bar">
            <div class="tracker-progress"></div>
            <div class="tracker-step">Order Placed</div>
            <div class="tracker-step">Prep</div>
            <div class="tracker-step">Bake</div>
            <div class="tracker-step">Box</div>
            <div class="tracker-step">Delivery</div>
        </div>
        <p>Order ID: <?php echo $order['id']; ?> | Status: <?php echo ucfirst($order['status']); ?></p>
    </div>
    <?php if ($order['status'] === 'cancelled'): ?>
        <p>Your order was cancelled. A voucher has been issued.</p>
        <?php
        $stmt = $pdo->prepare("INSERT INTO vouchers (user_id, code, amount, expiry_date) VALUES (?, ?, ?, DATE_ADD(CURDATE(), INTERVAL 30 DAY))");
        $stmt->execute([$userId, 'REFUND' . $order['id'], $order['total_amount'] * 0.5, date('Y-m-d')]);
        ?>
    <?php endif; ?>
<?php else: ?>
    <p>No active orders to track.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>