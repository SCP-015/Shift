<?php
require_once '../src/ShiftFunctions.php';

$shiftPatterns = [
    "001" => str_split("PPSSMML"),
    "002" => str_split("SSMMLPS"),
    "003" => str_split("MMPLPPM"),
    "004" => str_split("LPPPSSPLSSPSSP")
];

$users = [
    "001" => "Ahmad",
    "002" => "Widi",
    "003" => "Yono",
    "004" => "Yohan"
];
$patternStart = "2024-12-26";

$userId = isset($_GET['user_id']) ? trim($_GET['user_id']) : null;
$date = trim($_GET['date']) ?? null;

if (!$userId || !$date) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "user_id dan date wajib diisi."]);
    exit;
}

if (!isset($users[$userId])) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User tidak ditemukan."]);
    exit;
}

$shift = getShiftOnDate($userId, $date, $shiftPatterns, $patternStart);

echo json_encode([
    "status" => "success",
    "data" => [
        "id" => $userId,
        "name" => $users[$userId],
        "date" => $date,
        "shift" => $shift
    ],
    "http_code" => 200
], JSON_PRETTY_PRINT);
