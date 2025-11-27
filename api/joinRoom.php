<?php
header('Content-Type: application/json');

$id = preg_replace('/[^a-z0-9]/i', '', $_GET['id'] ?? '');
$user = trim($_GET['user'] ?? 'Invité');
$user = substr(preg_replace('/[^a-zA-Z0-9 _-]/', '', $user), 0, 30);
$pwd = $_GET['password'] ?? '';

$file = __DIR__ . "/../data/rooms/{$id}.json";
if (!file_exists($file)) { http_response_code(404); echo json_encode(["error"=>"Salle introuvable"]); exit; }

$raw = file_get_contents($file);
$data = json_decode($raw, true);
if ($data === null) { http_response_code(500); echo json_encode(["error"=>"JSON invalide"]); exit; }

// vérification mot de passe si présent
if (!empty($data['password_hash'])) {
    if ($pwd === '' || !password_verify($pwd, $data['password_hash'])) {
        http_response_code(403);
        echo json_encode(["error"=>"Mot de passe invalide"]);
        exit;
    }
}

// ajoute user si absent
if (!in_array($user, $data['users'])) {
    $data['users'][] = $user;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
}

echo json_encode(["ok"=>true, "user"=>$user]);
