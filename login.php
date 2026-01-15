<?php
require_once "pdo.php";
session_start();
if (isset($_POST['cancel'])) {
    header("Location: app.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = hash('md5', 'XyZzy12*_php123');
$failure = false;

if (isset($_POST['email']) && isset($_POST['pass'])) {
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "User name and password are required";
    } else if (strpos($_POST['email'], "@") === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Anass Bouziani Resume Registry - Login</title>
  <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Anass Bouziani Resume Registry - Login</h1>
