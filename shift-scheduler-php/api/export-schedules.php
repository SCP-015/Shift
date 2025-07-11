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
    echo "start_date dan end_date wajib diisi.";
    exit;
}

// Gunakan nama file tetap
$tempFile = "../data/schedule.csv";

if ($userId) {
    if (!isset($users[$userId])) {
        http_response_code(404);
        echo "User tidak ditemukan.";
        exit;
    }
    exportScheduleCSV([$userId => $users[$userId]], $shiftPatterns, $startDate, $endDate, $patternStart, $tempFile);
} else {
    exportScheduleCSV($users, $shiftPatterns, $startDate, $endDate, $patternStart, $tempFile);
}

// Kirim file ke browser (opsional)
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="schedule.csv"');
readfile($tempFile);

// Jangan unlink(), supaya tetap tersimpan
// unlink($tempFile);
