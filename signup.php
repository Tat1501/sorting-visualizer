<?php
$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

$username = $data['username'];
$password = $data['password'];

$users = file_exists("users.json") ? json_decode(file_get_contents("users.json"), true) : [];

if (isset($users[$username])) {
    echo json_encode(["success" => false, "message" => "Username already exists"]);
    exit;
}

$users[$username] = $password;
file_put_contents("users.json", json_encode($users));

echo json_encode(["success" => true]);
