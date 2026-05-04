<?php
include_once 'DBConn.php';
session_start();

$error = '';
$success = '';

// LOGIN LOGIC
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM tblUser WHERE email = '$email'";
        $result = $conn->query($sql);
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password_hash'])) {
                
                if ($user['verification_status'] === 'active' || $user['role'] === 'admin') {
                    
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['role'] = $user['role'];
                    
                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                        exit();
                    } else {
                        header("Location: products.php");
                        exit();
                    }
                    
                } else {
                    $error = "Your account is pending verification.";
                }
                
            } else {
                $error = "Incorrect password.";
            }
            
        } else {
            $error = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="page-wrapper"> <!-- CENTERING WRAPPER -->

    <div class="login-container">

        <!-- HEADER -->
        <div class="login-left">
            <h1>Welcome Back</h1>
            <p>Login to your Pastimes account</p>
        </div>

        <!-- ERROR MESSAGE -->
        <?php if ($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- SUCCESS MESSAGE -->
        <?php if ($success): ?>
            <div class="alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form method="POST" action="login.php">

            <label>Email:</label>
            <input type="email" name="email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="login" class="btn-primary">Login</button>

        </form>

        <!-- FOOTER -->
       <div class="form-footer">
    <p>Already have an account? <a href="login.php">Login here</a></p>
    <p style="margin-top:10px;">
        <a href="admin.php">Admin Login</a>
    </p>
</div>

    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>