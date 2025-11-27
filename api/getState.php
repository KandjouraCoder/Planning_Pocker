<?php
header('Content-Type: application/json');

$id = preg_replace('/[^a-z0-9]/i', '', $_GET['id'] ?? '');
$file = __DIR__ . "/../data/rooms/{$id}.json";

if (!file_exists($file)) { http_response_code(404); echo json_encode(["error"=>"Salle introuvable"]); exit; }

$raw = file_get_contents($file);
$data = json_decode($raw, true);
if ($data === null) { http_response_code(500); echo json_encode(["error"=>"JSON invalide","raw"=>$raw]); exit; }

$data["votes"] = $data["votes"] ?? [];
$data["users"] = $data["users"] ?? [];
$data["story"] = $data["story"] ?? "";
$data["revealed"] = $data["revealed"] ?? false;

$stats = ["min" => null, "max" => null, "avg" => null];
if ($data["revealed"] === true) {
    $nums = [];
    foreach ($data["votes"] as $v) {
        if (is_numeric($v)) $nums[] = intval($v);
    }
    if (!empty($nums)) {
        sort($nums);
        $stats["min"] = $nums[0];
        $stats["max"] = end($nums);
        $stats["avg"] = round(array_sum($nums) / count($nums), 2);
    }
}

echo json_encode([
    "id" => $id,
    "users" => $data["users"],
    "votes" => $data["votes"],
    "story" => $data["story"],
    "revealed" => $data["revealed"],
    "stats" => $stats
]);
