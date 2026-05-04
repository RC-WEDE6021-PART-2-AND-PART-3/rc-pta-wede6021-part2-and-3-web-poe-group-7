<?php
include_once 'DBConn.php';
session_start();

$error = '';
$success = '';
$adminLoggedIn = false;

// CHECK SESSION
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $adminLoggedIn = true;
}

/* =========================
   ADMIN LOGIN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {

    $email = trim($_POST['admin_email']);
    $password = $_POST['admin_password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {

        $sql = "SELECT * FROM tblUser WHERE email = '$email' AND role = 'admin'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {

            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password_hash'])) {

                $_SESSION['user_id'] = $admin['user_id'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['first_name'] = $admin['first_name'];
                $_SESSION['last_name'] = $admin['last_name'];
                $_SESSION['role'] = 'admin';

                $adminLoggedIn = true;

            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "Admin account not found.";
        }
    }
}

/* =========================
   VERIFY USER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_user']) && $adminLoggedIn) {

    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    $status = ($action === 'activate') ? 'active' : 'rejected';

    $sql = "UPDATE tblUser SET verification_status = '$status' WHERE user_id = '$user_id'";

    if ($conn->query($sql)) {
        $success = "User updated successfully.";
    } else {
        $error = "Failed to update user.";
    }
}

/* =========================
   ADD USER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user']) && $adminLoggedIn) {

    $first = trim($_POST['new_first_name']);
    $last = trim($_POST['new_last_name']);
    $email = trim($_POST['new_email']);
    $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $role = $_POST['new_role'];
    $status = $_POST['new_status'];

    $sql = "INSERT INTO tblUser (email, password_hash, first_name, last_name, role, verification_status)
            VALUES ('$email', '$password', '$first', '$last', '$role', '$status')";

    if ($conn->query($sql)) {
        $success = "User added successfully.";
    } else {
        $error = "Error adding user.";
    }
}

/* =========================
   DELETE USER
========================= */
if (isset($_GET['delete_id']) && $adminLoggedIn) {

    $id = $_GET['delete_id'];

    $sql = "DELETE FROM tblUser WHERE user_id = '$id' AND role != 'admin'";

    if ($conn->query($sql)) {
        $success = "User deleted.";
    } else {
        $error = "Cannot delete user.";
    }
}

/* =========================
   FETCH USERS
========================= */
$users = [];
if ($adminLoggedIn) {
    $result = $conn->query("SELECT * FROM tblUser ORDER BY created_at DESC");

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="<?php echo $adminLoggedIn ? 'admin-mode' : ''; ?>">

<div class="page-wrapper">
<div class="login-container">

<?php if (!$adminLoggedIn): ?>

    <!-- ================= LOGIN ================= -->
    <div class="login-left">
        <h1>Admin Login</h1>
        <p>Access system control panel</p>
    </div>

    <?php if ($error): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Email:</label>
        <input type="email" name="admin_email" required>

        <label>Password:</label>
        <input type="password" name="admin_password" required>

        <button type="submit" name="admin_login" class="btn-primary">
            Login
        </button>

    </form>

    <div class="form-footer">
        <a href="login.php">Back to User Login</a>
    </div>

<?php else: ?>

    <!-- ================= DASHBOARD ================= -->

    <div class="login-left">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['first_name']; ?></p>
    </div>

    <div class="admin-section">

        <p>
            <a href="logout.php">Logout</a> |
            <a href="products.php">View Products</a>
        </p>

        <?php if ($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- ADD USER -->
        <h3>Add User</h3>

        <form method="POST">

            <input type="text" name="new_first_name" placeholder="First Name" required>
            <input type="text" name="new_last_name" placeholder="Last Name" required>
            <input type="email" name="new_email" placeholder="Email" required>
            <input type="password" name="new_password" placeholder="Password" required>

            <select name="new_role">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>

            <select name="new_status">
                <option value="pending">Pending</option>
                <option value="active">Active</option>
            </select>

            <button type="submit" name="add_user" class="btn-primary">
                Add User
            </button>

        </form>

        <!-- USERS TABLE -->
        <h3>Users</h3>

        <table class="admin-table">

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($users as $user): ?>
            <tr>

                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['verification_status']; ?></td>

                <td>

                    <?php if ($user['verification_status'] === 'pending'): ?>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="action" value="activate">
                            <button class="btn-primary" name="verify_user">Verify</button>
                        </form>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="action" value="reject">
                            <button class="btn-danger" name="verify_user">Reject</button>
                        </form>

                    <?php endif; ?>

                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href="admin.php?delete_id=<?php echo $user['user_id']; ?>"
                           onclick="return confirm('Delete user?');">
                            Delete
                        </a>
                    <?php endif; ?>

                </td>

            </tr>
            <?php endforeach; ?>

        </table>

    </div>

<?php endif; ?>

</div>
</div>

</body>
</html>

<?php $conn->close(); ?>