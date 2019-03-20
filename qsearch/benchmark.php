<?php

require './Math.php';

//Реализация поиска медианы через полную сортировку массива
function fullSortMedian(array $nums): float
{
    sort($nums);
    $cnt = count($nums);
    if ($cnt == 0) {
        return 0;
    }

    if ($cnt == 1) {
        return $nums[0];
    }

    $mid = floor($cnt / 2);
    if ($cnt % 2 == 0) {
        return ($nums[$mid - 1] + $nums[$mid]) / 2;
    } 

    return $nums[$mid];
}

//метод для генерации набора тестовых данных
function generateArray(int $length): array 
{
    $nums = [];
    while ($length-- > 0) {
        $nums[] = rand(-1000, 1000);
    }

    return $nums;
}

//тесты
$lengths = [100, 1000, 10000, 1000001, 1000000, 30000001];
$res = [];
foreach ($lengths as $length) {
    $nums = generateArray($length);
    $start = microtime(true);
    fullSortMedian($nums);
    $res[$length] = [microtime(true) - $start];
    $start = microtime(true);
    Math::median($nums);
    $res[$length][1] = microtime(true) - $start;
}

printf("|%-10s|%-17s|%-17s|\n", "length", "full", "quick");
$mask = "|%-10s|%-1.15f|%-1.15f|\n";
foreach ($res as $length => $times) {
    printf($mask, $length, $times[0], $times[1]);
}
