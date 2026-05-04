<?php

include_once 'DBConn.php';
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
   ADD TO CART
========================= */
if (isset($_GET['add_to_cart'])) {

    $id = $_GET['add_to_cart'];

    $sql = "SELECT * FROM tblClothes WHERE clothes_id = '$id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        $item = $result->fetch_assoc();

        $found = false;

        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['clothes_id'] == $id) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'clothes_id' => $item['clothes_id'],
                'brand' => $item['brand'],
                'category' => $item['category'],
                'size' => $item['size'],
                'price' => $item['price'],
                'image_path' => $item['image_path'],
                'quantity' => 1
            ];
        }
    }

    header("Location: products.php?added=1");
    exit();
}

/* =========================
   FETCH PRODUCTS
========================= */
$items = [];

$sql = "SELECT * FROM tblClothes WHERE status='Active' ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

/* =========================
   CART COUNT
========================= */
$cartCount = 0;
foreach ($_SESSION['cart'] as $c) {
    $cartCount += $c['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- HEADER -->
<div class="header-container">
    <h1>Pastimes</h1>

    <div>
        <?php if ($loggedIn): ?>
            <span>Welcome, <strong><?php echo $userName; ?></strong></span>
            <a href="cart.php">🛒 Cart (<?php echo $cartCount; ?>)</a>
            <a href="logout.php">Logout</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>

        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- PAGE -->
<div class="container">

    <h2 class="page-title">Available Items</h2>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Item added to cart</div>
    <?php endif; ?>

    <!-- PRODUCT GRID -->
    <div class="product-grid">

        <?php foreach ($items as $item): ?>

        <div class="product-card">

            <img src="<?php echo $item['image_path']; ?>" alt="product">

            <div class="product-info">

                <h3><?php echo $item['brand']; ?></h3>

                <p class="meta">
                    <?php echo $item['category']; ?> •
                    Size <?php echo $item['size']; ?>
                </p>

                <p class="rating">
                    Condition: <?php echo $item['condition_rating']; ?>/5
                </p>

                <p class="price">
                    R<?php echo number_format($item['price'], 2); ?>
                </p>

                <a class="btn-cart"
                   href="products.php?add_to_cart=<?php echo $item['clothes_id']; ?>">
                    🛒 Add to Cart
                </a>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>