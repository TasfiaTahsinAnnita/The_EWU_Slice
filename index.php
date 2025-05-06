<?php include 'includes/header.php'; ?>

<div class="promo-carousel"></div>

<div class="hero">
    <h2>Order Your Favorite Pizza Today!</h2>
    <p>Fast, fresh, and delivered to your door.</p>
    <form method="POST" action="menu.php">
        <label>Select Store:</label>
        <select name="store_id">
            <?php
            include 'includes/db_connect.php';
            $stmt = $pdo->query("SELECT * FROM stores");
            while ($store = $stmt->fetch()) {
                echo "<option value='{$store['id']}'>{$store['name']} ({$store['city']})</option>";
            }
            ?>
        </select>
        <button type="submit">Go to Menu</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>