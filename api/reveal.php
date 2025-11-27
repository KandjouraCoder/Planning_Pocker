<?php
header('Content-Type: application/json');

$id = preg_replace('/[^a-z0-9]/i', '', $_GET['id'] ?? '');
$pwd = $_GET['password'] ?? '';

$file = __DIR__ . "/../data/rooms/{$id}.json";
if (!file_exists($file)) { http_response_code(404); echo json_encode(["error"=>"Salle introuvable"]); exit; }

$data = json_decode(file_get_contents($file), true);
if ($data === null) { http_response_code(500); echo json_encode(["error"=>"JSON invalide"]); exit; }

if (!empty($data['password_hash'])) {
    if ($pwd === '' || !password_verify($pwd, $data['password_hash'])) {
        http_response_code(403);
        echo json_encode(["error"=>"Mot de passe invalide"]);
        exit;
    }
}

$data['revealed'] = true;
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);

echo json_encode(["ok"=>true]);
