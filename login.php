<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

$users = json_decode(file_get_contents("users.json"), true);
$username = $data['username'];
$password = $data['password'];

if (isset($users[$username]) && $users[$username] === $password) {
    $_SESSION['username'] = $username;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
