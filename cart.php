
<?php
session_start();

$loggedIn = isset($_SESSION['user_id']);
$userName = '';

if ($loggedIn) {
    $userName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   REMOVE ITEM
========================= */
if (isset($_GET['remove'])) {

    $id = $_GET['remove'];

    unset($_SESSION['cart'][$id]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: cart.php?removed=1");
    exit();
}

/* =========================
   CLEAR CART
========================= */
if (isset($_GET['clear'])) {

    $_SESSION['cart'] = [];

    header("Location: cart.php?cleared=1");
    exit();
}

/* =========================
   TOTAL CALCULATION
========================= */
$total = 0;

foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart - Pastimes</title>
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
            <a href="checkout.php">Checkout</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>

</div>

<!-- PAGE -->
<div class="container">

<h2 class="page-title">Your Cart</h2>

<?php if (isset($_GET['removed'])): ?>
    <div class="alert-success">Item removed from cart</div>
<?php endif; ?>

<?php if (isset($_GET['cleared'])): ?>
    <div class="alert-error">Cart cleared</div>
<?php endif; ?>

<?php if (count($_SESSION['cart']) > 0): ?>

<div class="cart-layout">

    <!-- LEFT: ITEMS -->
    <div class="cart-items">

        <?php foreach ($_SESSION['cart'] as $key => $item): ?>

        <div class="cart-card">

            <img src="<?php echo $item['image_path']; ?>" alt="item">

            <div class="cart-info">

                <h3><?php echo $item['brand']; ?></h3>

                <p class="meta">
                    Size: <?php echo $item['size']; ?> |
                    Qty: <?php echo $item['quantity']; ?>
                </p>

                <p class="price">
                    R<?php echo number_format($item['price'], 2); ?>
                </p>

                <p class="subtotal">
                    Subtotal: R<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </p>

            </div>

            <div class="cart-actions">

                <a href="cart.php?remove=<?php echo $key; ?>"
                   class="btn-danger"
                   onclick="return confirm('Remove item?');">
                    Remove
                </a>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="cart-summary summary-box">

        <h3>Order Summary</h3>

        <div class="summary-line">
            <span>Items</span>
            <span><?php echo count($_SESSION['cart']); ?></span>
        </div>

        <div class="summary-line">
            <span>Subtotal</span>
            <span>R<?php echo number_format($total, 2); ?></span>
        </div>

        <hr style="margin: 12px 0; border: none; border-top: 1px solid #e2e8f0;">

        <div class="cart-total">
            <span>Total</span>
            <span>R<?php echo number_format($total, 2); ?></span>
        </div>

        <a href="checkout.php" class="btn-primary">
            Proceed to Checkout
        </a>

        <a href="cart.php?clear=1"
           class="btn-danger"
           onclick="return confirm('Clear cart?');">
            Clear Cart
        </a>

    </div>

</div>

<?php else: ?>

<div class="empty-cart">
    <p>Your cart is empty</p>
    <a href="products.php" class="btn-primary">Browse Products</a>
</div>

<?php endif; ?>

</div>

</body>
</html>