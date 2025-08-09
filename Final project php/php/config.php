<?php
session_start();

$host = 'localhost';
$dbname = 'php_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function checkLogin() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!checkLogin()) {
        header('Location: index.php');
        exit();
    }
}

function clean($data) {
    return htmlspecialchars(trim($data));
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>
