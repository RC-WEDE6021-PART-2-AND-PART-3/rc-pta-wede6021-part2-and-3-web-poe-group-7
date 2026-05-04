<?php

include_once 'DBConn.php';

echo "<h2>Resetting All Users</h2>";

// Delete all existing users
$conn->query("DELETE FROM tblUser");
echo "<p>Old users deleted.</p>";

// Create 5 users with passwords hashed on YOUR system
// Format: email, password, first_name, last_name, role, status

$users = [
    ['john@email.com', 'Password123', 'John', 'Doe', 'customer', 'pending'],
    ['jane@email.com', 'Password123', 'Jane', 'Smith', 'customer', 'pending'],
    ['admin@pastimes.co.za', 'Password123', 'Admin', 'User', 'admin', 'active'],
    ['bob@email.com', 'Password123', 'Bob', 'Marley', 'customer', 'pending'],
    ['lebo@email.com', 'Password123', 'Lebo', 'Khumalo', 'customer', 'active']
];

$count = 0;

foreach ($users as $u) {
    // Hash the password on YOUR system
    $hash = password_hash($u[1], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO tblUser (email, password_hash, first_name, last_name, role, verification_status) 
            VALUES ('$u[0]', '$hash', '$u[2]', '$u[3]', '$u[4]', '$u[5]')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>✅ Created user: $u[0] (password: $u[1])</p>";
        $count++;
    } else {
        echo "<p>❌ Error: " . $conn->error . "</p>";
    }
}

echo "<br><p><strong>$count users created successfully!</strong></p>";
echo "<p>Now go to <a href='login.php'>Login Page</a> and test:</p>";
echo "<ul>";
echo "<li>Admin: admin@pastimes.co.za / Password123</li>";
echo "<li>Customer: lebo@email.com / Password123 (already active)</li>";
echo "<li>Customer: john@email.com / Password123 (pending - needs admin verification)</li>";
echo "</ul>";

$conn->close();
?>