<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT id FROM orders WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$userId]);
    $orderId = $stmt->fetchColumn();

    if (!$orderId) {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, store_id, total_amount) VALUES (?, ?, 0)");
        $stmt->execute([$userId, $_SESSION['store_id'] ?? 1]);
        $orderId = $pdo->lastInsertId();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $itemId = (int)$_POST['item_id'];
        $itemPrice = (float)$_POST['item_price'];

        $stmt = $pdo->prepare("SELECT id, quantity FROM order_items WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $itemId]);
        $item = $stmt->fetch();

        if ($item) {
            $stmt = $pdo->prepare("UPDATE order_items SET quantity = quantity + 1 WHERE id = ?");
            $stmt->execute([$item['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, 1, ?)");
            $stmt->execute([$orderId, $itemId, $itemPrice]);
        }

        $stmt = $pdo->prepare("UPDATE orders SET total_amount = (SELECT SUM(quantity * price) FROM order_items WHERE order_id = ?) WHERE id = ?");
        $stmt->execute([$orderId, $orderId]);

        echo "Cart updated";
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $itemId = (int)$_POST['item_id'];
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ? AND menu_item_id = ?");
        $stmt->execute([$orderId, $itemId]);

        $stmt = $pdo->prepare("UPDATE orders SET total_amount = (SELECT SUM(quantity * price) FROM order_items WHERE order_id = ?) WHERE id = ?");
        $stmt->execute([$orderId, $orderId]);

        echo "Item removed";
        exit;
    }
}

$cartItems = [];
$total = 0;

if ($userId) {
    $stmt = $pdo->prepare("SELECT o.id AS order_id, oi.menu_item_id, mi.name, oi.quantity, oi.price 
                           FROM orders o 
                           JOIN order_items oi ON o.id = oi.order_id 
                           JOIN menu_items mi ON oi.menu_item_id = mi.id 
                           WHERE o.user_id = ? AND o.status = 'pending'");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT total_amount FROM orders WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$userId]);
    $total = $stmt->fetchColumn() ?: 0;
}
?>

<h2>Your Cart</h2>
<div id="cart-items">
    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <span><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>) - ৳<?php echo ($item['price'] * $item['quantity']); ?></span>
                <button onclick="removeFromCart(<?php echo $item['menu_item_id']; ?>)">Remove</button>
            </div>
        <?php endforeach; ?>
        <p>Suggestion: Add a drink for ৳20</p>
    <?php endif; ?>
</div>
<div id="cart-total">Total: ৳<?php echo number_format($total, 2); ?></div>
<?php if (!empty($cartItems)): ?>
    <button onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>