<?php

class Math 
{
    /**
     * Вычисление медианы массива
     * @param float[]|string[] $nums - массив данных, числа, либо строки содержащие числа
     * @return float медиана массива
    */
    public static function median(array $nums): float
    {
        $cnt = count($nums);
        if ($cnt == 0) {
            return 0;
        }

        if ($cnt == 1) {
            return $nums[0];
        }

        $mid = floor($cnt / 2);
        if ($cnt % 2 == 0) {
            self::qsearch($nums, $mid, 0, $cnt - 1);
            $larger = $nums[$mid];
            $lower = $nums[$mid - 1];
            return ($lower + $larger) / 2;
        } 

        self::qsearch($nums, $mid, 0, $cnt - 1);
        return $nums[$mid];
    }

    protected static function qsearch(array &$arr, int $k, int $start, int $end): void
    {
        if ($start >= $end || $k > $end || $k - 1 < $start) {
            return;
        }

        $l = $start; 
        $r = $end;
        $pivot = $arr[$start];
        while ($l < $r) {
            if ($arr[$l] <= $pivot) {
                $l++;
            } elseif ($arr[$r] > $pivot) {
                $r--;
            } else {
                self::swap($arr, $l, $r);
                $l++;
                $r--;
            }
        }

        if ($arr[$l] > $arr[$start]) {
            $l--;
        }

        self::swap($arr, $l, $start);
        self::qsearch($arr, $k, $start, $l - 1);
        self::qsearch($arr, $k, $l + 1, $end);
    }
       
    protected static function swap(array &$arr, int $i, int $j): void
    {
        $t = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $t;
    }
}