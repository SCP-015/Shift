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

$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;
$userId = isset($_GET['user_id']) ? trim($_GET['user_id']) : null;

if (!$startDate || !$endDate) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "start_date dan end_date wajib diisi."]);
    exit;
}

$data = [];

if ($userId) {
    if (!isset($users[$userId])) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User ID tidak ditemukan."]);
        exit;
    }
    $data[] = [
        "id" => $userId,
        "name" => $users[$userId],
        "schedule" => getScheduleRange($userId, $startDate, $endDate, $shiftPatterns, $patternStart)
    ];
} else {
    foreach ($users as $id => $name) {
        $data[] = [
            "id" => $id,
            "name" => $name,
            "schedule" => getScheduleRange($id, $startDate, $endDate, $shiftPatterns, $patternStart)
        ];
    }
}

echo json_encode([
    "status" => "success",
    "data" => $data,
    "http_code" => 200
], JSON_PRETTY_PRINT);
