<?php


namespace App\Utils;


class Math
{
    /**
     * Calc Standard Dev.
     *
     * @param $arr
     *
     * @return float
     */
    public static function calcSD($arr)
    {
        $elemCount = count($arr);
        $variance = 0.0;
        $average = array_sum($arr) / $elemCount;
        foreach ($arr as $elem) {
            $variance += pow(($elem - $average), 2);
        }
        return (float)sqrt($variance / $elemCount);
    }

    public static function normalize($val, $max, $min)
    {
        return (float)($val - $min) / ($max - $min);
    }
}
