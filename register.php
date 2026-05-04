<<?php
// register.php

include_once 'DBConn.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    }
    elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    }
    else {
        $checkSQL = "SELECT email FROM tblUser WHERE email = '$email'";
        $checkResult = $conn->query($checkSQL);
        
        if ($checkResult->num_rows > 0) {
            $error = "This email is already registered.";
        }
        else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insertSQL = "INSERT INTO tblUser (email, password_hash, first_name, last_name, role, verification_status) 
                          VALUES ('$email', '$hashed_password', '$first_name', '$last_name', 'customer', 'pending')";
            
            if ($conn->query($insertSQL) === TRUE) {
                $success = "Registration successful! Wait for admin approval.";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Pastimes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="page-wrapper">

    <div class="login-container">

        <!-- HEADER -->
        <div class="login-left">
            <h1>Create Account</h1>
            <p>Join the Pastimes community</p>
        </div>

        <!-- ERROR MESSAGE -->
        <?php if ($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- SUCCESS MESSAGE -->
        <?php if ($success): ?>
            <div class="alert-success">
                <?php echo $success; ?><br><br>
                <a href="login.php">Go to Login</a>
            </div>
        <?php else: ?>

        <!-- REGISTER FORM -->
        <form method="POST" action="register.php">

            <label>First Name:</label>
            <input type="text" name="first_name"
                value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>"
                required>

            <label>Last Name:</label>
            <input type="text" name="last_name"
                value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>"
                required>

            <label>Email:</label>
            <input type="email" name="email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required>

            <label>Password (min 6 characters):</label>
            <input type="password" name="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="btn-primary">Register</button>

        </form>

        <!-- FOOTER -->
      <div class="form-footer">
    <p>Already have an account? <a href="login.php">Login here</a></p>
    <p style="margin-top:10px;">
        <a href="admin.php">Admin Login</a>
    </p>
</div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>