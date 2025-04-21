<?php include 'includes/header.php'; ?>
<h2>Welcome to Domino's Pizza Platform</h2>
<p>Order fresh groceries and meals with ease! Select your store and start shopping.</p>
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
<?php include 'includes/footer.php'; ?>