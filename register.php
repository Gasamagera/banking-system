<?php
// 1. Connect to the database
$conn = new mysqli("localhost", "root", "", "bank_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Get form data
$fullName = $_POST['full_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$telephone = $_POST['telephone'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

// 3. Insert data
$sql = "INSERT INTO users (full_name, username, email, telephone, address, password)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $fullName, $username, $email, $telephone, $address, $password);

if ($stmt->execute()) {
    header("Location: index.html?show=login");
    exit();
}
else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
