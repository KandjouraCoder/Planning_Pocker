<?php
// createRoom.php
$id = substr(md5(uniqid()), 0, 6);

$pwd = trim($_POST['password'] ?? '');
$pwd_hash = $pwd !== '' ? password_hash($pwd, PASSWORD_DEFAULT) : null;

$data = [
    "id" => $id,
    "users" => [],
    "votes" => [],
    "revealed" => false,
    "story" => "",
    "password_hash" => $pwd_hash
];

file_put_contents(__DIR__ . "/../data/rooms/{$id}.json", json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);

// redirige vers salle en passant password (pratique local dev)
$redir = "../salle.php?id={$id}&admin=1";
if ($pwd !== '') $redir .= "&password=" . urlencode($pwd);
header("Location: {$redir}");
exit;
