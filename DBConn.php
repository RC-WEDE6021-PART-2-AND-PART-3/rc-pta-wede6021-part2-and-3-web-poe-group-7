<?php

// These are the details to connect to your MySQL database
$host = "localhost";  // Server location
$user = "root";       // Default XAMPP username
$pass = "";           // Default XAMPP password is blank/empty
$db   = "clothingstore"; // CHANGE THIS TO LOWERCASE

// Create the connection
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>