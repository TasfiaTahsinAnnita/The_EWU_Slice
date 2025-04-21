<?php
include 'includes/header.php';
include 'includes/db_connect.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    $orderId = $_POST['order_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO reviews (order_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$orderId, $userId, $rating, $comment]);
}

$stmt = $pdo->query("SELECT r.*, o.id AS order_id, mi.name AS item_name 
                     FROM reviews r 
                     JOIN orders o ON r.order_id = o.id 
                     JOIN order_items oi ON o.id = oi.order_id 
                     JOIN menu_items mi ON oi.menu_item_id = mi.id");
$reviews = $stmt->fetchAll();

$orders = [];
if ($userId) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'delivered'");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
}
?>

<h2>Reviews & Ratings</h2>
<div class="review-section">
    <h3>Submit a Review</h3>
    <?php if ($userId && !empty($orders)): ?>
        <form method="POST">
            <label>Order:</label>
            <select name="order_id" required>
                <?php foreach ($orders as $order): ?>
                    <option value="<?php echo $order['id']; ?>">Order #<?php echo $order['id']; ?></option>
                <?php endforeach; ?>
            </select><br>
            <label>Rating (1-5): <input type="number" name="rating" min="1" max="5" required></label><br>
            <label>Comment: <textarea name="comment"></textarea></label><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php else: ?>
        <p>No delivered orders to review. Please log in or complete an order.</p>
    <?php endif; ?>
</div>

<div class="review-section">
    <h3>Customer Reviews</h3>
    <?php if (empty($reviews)): ?>
        <p>No reviews yet.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div>
                <p><strong><?php echo $review['item_name']; ?></strong> - Rating: <?php echo $review['rating']; ?>/5</p>
                <p><?php echo $review['comment'] ?: 'No comment'; ?></p>
                <p>Posted on: <?php echo $review['created_at']; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>