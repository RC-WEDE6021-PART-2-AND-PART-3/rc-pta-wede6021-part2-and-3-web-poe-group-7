<?php

include_once 'DBConn.php';

echo "<h2>Table Management - tblUser</h2>";

// STEP 1: Drop the table if it exists
$dropSQL = "DROP TABLE IF EXISTS tblUser";
if ($conn->query($dropSQL) === TRUE) {
    echo "<p>✅ Old tblUser table dropped (if it existed).</p>";
} else {
    echo "<p>❌ Error dropping table: " . $conn->error . "</p>";
}


$createSQL = "CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role VARCHAR(20) DEFAULT 'customer',
    verification_status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createSQL) === TRUE) {
    echo "<p>✅ tblUser table created successfully.</p>";
} else {
    echo "<p>❌ Error creating table: " . $conn->error . "</p>";
}

// STEP 3: Load data from userData.txt
$filename = 'userData.txt';

if (file_exists($filename)) {
    $file = fopen($filename, 'r');
    
    // Skip the header line
    $header = fgets($file);
    
    $count = 0;
    
    // Read each line and insert into database
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        
        // Skip empty lines
        if (empty($line)) {
            continue;
        }
        
        // Split the line by comma
        $data = str_getcsv($line);
        
        // Get each value
        $email = $data[0];
        $password_hash = $data[1];
        $first_name = $data[2];
        $last_name = $data[3];
        $role = $data[4];
        $verification_status = $data[5];
        
        // Insert into database
        $insertSQL = "INSERT INTO tblUser (email, password_hash, first_name, last_name, role, verification_status) 
                      VALUES ('$email', '$password_hash', '$first_name', '$last_name', '$role', '$verification_status')";
        
        if ($conn->query($insertSQL) === TRUE) {
            $count++;
        } else {
            echo "<p>❌ Error on line: $line - " . $conn->error . "</p>";
        }
    }
    
    fclose($file);
    echo "<p>✅ $count users loaded from $filename</p>";
    
} else {
    echo "<p>❌ File $filename not found!</p>";
}

echo "<br><a href='login.php'>Go to Login Page</a>";

$conn->close();
?>