<?php
session_start();
$usersFile = 'users.json';

// Read or initialize users
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$username = $_POST['username'];
$password = $_POST['password'];
$action = $_POST['action'];

if ($action === 'signup') {
    if (isset($users[$username])) {
        echo "<script>alert('User already exists!'); window.location.href='visualizer.php';</script>";
        exit;
    }
    $users[$username] = password_hash($password, PASSWORD_DEFAULT);
    file_put_contents($usersFile, json_encode($users));
    $_SESSION['username'] = $username;
    header("Location: visualizer.php");
    exit;

} elseif ($action === 'login') {
    if (!isset($users[$username]) || !password_verify($password, $users[$username])) {
        echo "<script>alert('Invalid credentials!'); window.location.href='visualizer.php';</script>";
        exit;
    }
    $_SESSION['username'] = $username;
    header("Location: visualizer.php");
    exit;
}
?>
