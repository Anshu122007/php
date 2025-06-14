<?php

$host = 'localhost';
$db = 'student_portal'; // Change to your database name
$user = 'your_username';  // Change to your DB username
$pass = 'your_password';  // Change to your DB password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

