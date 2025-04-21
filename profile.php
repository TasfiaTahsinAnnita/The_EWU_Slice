<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $contactNumber = $_POST['contact_number'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, contact_number = ?, password = ? WHERE id = ?");
    $stmt->execute([$username, $contactNumber, $password, $userId]);

    header("Location: profile.php?updated=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM vouchers WHERE user_id = ? AND used = FALSE AND expiry_date > CURDATE()");
$stmt->execute([$userId]);
$vouchers = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status != 'pending' ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<h2>Profile</h2>

<?php if (isset($_GET['updated'])): ?>
    <p style="color: green;">Profile updated successfully!</p>
<?php endif; ?>

<div class="profile-section">
    <h3>Update Profile</h3>
    <form method="POST">
        <label>Username: <input type="text" name="username" value="<?php echo $user['username']; ?>" required></label><br>
        <label>Contact Number: <input type="text" name="contact_number" value="<?php echo $user['contact_number'] ?: ''; ?>"></label><br>
        <label>New Password: <input type="password" name="password"></label><br>
        <button type="submit" name="update_profile">Update Profile</button>
    </form>
</div>

<div class="profile-section">
    <h3>Your Vouchers</h3>
    <?php if (empty($vouchers)): ?>
        <p>No active vouchers available.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($vouchers as $voucher): ?>
                <li>Code: <?php echo $voucher['code']; ?> | Amount: ৳<?php echo $voucher['amount']; ?> | Expires: <?php echo $voucher['expiry_date']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div class="profile-section">
    <h3>Previous Orders</h3>
    <?php if (empty($orders)): ?>
        <p>No previous orders found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($orders as $order): ?>
                <li>Order ID: <?php echo $order['id']; ?> | Total: ৳<?php echo $order['total_amount']; ?> | Status: <?php echo ucfirst($order['status']); ?> | Date: <?php echo $order['created_at']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<div class="profile-section">
    <h3>Track Current Order</h3>
    <p><a href="order_tracking.php">Click here to track your current order.</a></p>
</div>

<?php include 'includes/footer.php'; ?>