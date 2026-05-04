<?php

session_start();
include_once 'DBConn.php';

$loggedIn = isset($_SESSION['user_id']);
$userName = '';

if ($loggedIn) {
    $userName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   TOTALS
========================= */
$subtotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = ($subtotal > 500) ? 0 : 60;
$total = $subtotal + $shipping;

/* =========================
   PLACE ORDER
========================= */
$orderPlaced = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order']) && $loggedIn) {
    $_SESSION['cart'] = [];
    $orderPlaced = true;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- HEADER -->
<div class="header-container">

    <h1>Pastimes</h1>

    <div class="nav-links">
        <?php if ($loggedIn): ?>
            <span>Welcome, <strong><?php echo $userName; ?></strong></span>
            <a href="products.php">Continue Shopping</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

</div>

<!-- PAGE -->
<div class="container">

<?php if ($orderPlaced): ?>

    <div class="empty-cart">
        <h2 style="color:#38a169;">Order Placed Successfully</h2>
        <p style="margin-top:10px;">You will receive a confirmation shortly.</p>
        <a href="products.php" class="btn-primary" style="margin-top:20px; display:inline-block;">
            Continue Shopping
        </a>
    </div>

<?php elseif (count($_SESSION['cart']) === 0): ?>

    <div class="empty-cart">
        <p>Your cart is empty</p>
        <a href="products.php" class="btn-primary">Browse Products</a>
    </div>

<?php else: ?>

    <h2 class="page-title">Checkout</h2>

    <div class="checkout-layout">

        <!-- LEFT: ITEMS -->
        <div class="checkout-items">

            <?php foreach ($_SESSION['cart'] as $item): ?>

            <div class="checkout-card">

                <img src="<?php echo $item['image_path']; ?>" alt="item">

                <div class="checkout-info">

                    <h4><?php echo $item['brand']; ?></h4>

                    <p class="meta">
                        Size: <?php echo $item['size']; ?> |
                        Quantity: <?php echo $item['quantity']; ?>
                    </p>

                    <p class="price">
                        R<?php echo number_format($item['price'], 2); ?>
                    </p>

                    <p class="subtotal">
                        Subtotal: R<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </p>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- RIGHT: SUMMARY -->
        <div class="checkout-summary">

            <h3>Order Summary</h3>

            <div class="summary-line">
                <span>Subtotal</span>
                <span>R<?php echo number_format($subtotal, 2); ?></span>
            </div>

            <div class="summary-line">
                <span>Shipping</span>
                <span>
                    <?php if ($shipping == 0): ?>
                        <span style="color:#38a169;">FREE</span>
                    <?php else: ?>
                        R<?php echo number_format($shipping, 2); ?>
                    <?php endif; ?>
                </span>
            </div>

            <!-- STRONG VISUAL TOTAL -->
            <div class="summary-total">
                <span>Total</span>
                <span>R<?php echo number_format($total, 2); ?></span>
            </div>

            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 15px 0;">

            <?php if ($loggedIn): ?>

                <form method="POST">
                    <button type="submit" name="place_order" class="btn-primary">
                        Place Order
                    </button>
                </form>

            <?php else: ?>

                <p style="color:#e53e3e; font-size:14px;">
                    You must be logged in to complete this order.
                </p>

                <a href="login.php" class="btn-primary">
                    Login to Continue
                </a>

            <?php endif; ?>

        </div>

    </div>

<?php endif; ?>

</div>

</body>
</html>

<?php if (isset($conn)) $conn->close(); ?>