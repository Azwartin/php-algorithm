<?php

require './Math.php';

/**
 * Функция для проверки утверждений
 * @param float $expected ожидаемое значение
 * @param float $actual реальное значение 
 * @param float $delta допустимая разница
*/
function assertEquals(float $expected, float $actual, float $delta = 0.00001): void
{
    if (abs($expected -$actual) > $delta) {
        $line = debug_backtrace()[0]['line'];
        throw new \UnexpectedValueException("Expected $expected, got $actual at line $line");
    }

    echo "";
}

assertEquals(0, Math::median([]));
assertEquals(5, Math::median([5]));
assertEquals(6, Math::median([1,11]));
assertEquals(9, Math::median([9,1,11]));
assertEquals(3, Math::median([1,2,3,4,5]));
assertEquals(3, Math::median([5,4,3,2,1]));
assertEquals(0, Math::median([-5, 10, -10, 0, 5]));
assertEquals(0, Math::median([-10, 5, -5, 10, 0, 0]));
assertEquals(4.5, Math::median([1,3,5,2,7,2,1,7,5,23,6,4,2,5,8,0,6,2,4,6,8,4,2,8]));
