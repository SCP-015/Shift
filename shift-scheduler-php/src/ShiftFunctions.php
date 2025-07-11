<?php

function getShiftOnDate($userId, $date, $shiftPatterns, $patternStartDate)
{
    $start = new DateTime($patternStartDate);
    $target = new DateTime($date);
    $interval = $start->diff($target)->days;
    $pattern = $shiftPatterns[$userId];
    return $pattern[$interval % count($pattern)];
}

function getScheduleRange($userId, $startDate, $endDate, $shiftPatterns, $patternStartDate)
{
    $schedule = [];
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $pattern = $shiftPatterns[$userId];
    $current = clone $start;

    while ($current <= $end) {
        $interval = (new DateTime($patternStartDate))->diff($current)->days;
        $shift = $pattern[$interval % count($pattern)];
        $schedule[$current->format('Y-m-d')] = $shift;
        $current->modify('+1 day');
    }

    return $schedule;
}

function exportScheduleCSV($users, $shiftPatterns, $startDate, $endDate, $patternStartDate, $filename = 'schedule.csv')
{
    $fp = fopen($filename, 'w');
    $header = ['ID'];
    $dates = [];
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $current = clone $start;

    while ($current <= $end) {
        $dateStr = $current->format('Y-m-d');
        $header[] = $dateStr;
        $dates[] = $dateStr;
        $current->modify('+1 day');
    }

    fputcsv($fp, $header);

    foreach ($users as $id => $name) {
        $row = [$id];
        foreach ($dates as $date) {
            $row[] = getShiftOnDate($id, $date, $shiftPatterns, $patternStartDate);
        }
        fputcsv($fp, $row);
    }

    fclose($fp);
    return $filename;
}

function getScheduleAsJson($users, $shiftPatterns, $startDate, $endDate, $patternStartDate)
{
    $result = [];

    foreach ($users as $id => $name) {
        $schedule = getScheduleRange($id, $startDate, $endDate, $shiftPatterns, $patternStartDate);
        $result[] = [
            "id" => $id,
            "name" => $name,
            "schedule" => $schedule
        ];
    }

    return json_encode([
        "status" => "success",
        "data" => $result,
        "http_code" => 200
    ], JSON_PRETTY_PRINT);
}
