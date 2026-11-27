<?php

/**
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the MIT License. see <https://opensource.org/licenses/MIT>.
 *
 * @author       PresentKim (debe3721@gmail.com)
 * @link         https://github.com/PresentKim
 * @license      https://opensource.org/licenses/MIT MIT License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\formatter;

use function abs;
use function date;
use function implode;
use function intdiv;

final class KorStr{

    private function __construct(){
        // NOOP
    }

    /**
     * 주어진 수를 "12조 34억 56만 78"과 같은 한글로 변환
     *
     * 0은 "0", 음수는 "-12억 3456"처럼 맨 앞에 "-"를 붙여 변환한다.
     *
     * @param int         $number 변환할 값
     * @param string|null $unit   뒤에 붙일 단위, null이거나 빈 문자열이면 붙이지 않음
     * @param bool        $space  단위 사이에 공백을 넣을지 여부
     *
     * @return string
     */
    public static function number(int $number, ?string $unit = null, bool $space = true) : string{
        return self::join(
            self::split($number, [
                10000000000000000 => "경",
                1000000000000 => "조",
                100000000 => "억",
                10000 => "만",
            ], ""),
            $number < 0,
            $unit,
            $space
        );
    }

    /**
     * 주어진 unix timestamp를 "2021년 01월 01일 12시 34분 56초"와 같은 한글로 변환
     *
     * 시각은 PHP의 기본 시간대(date_default_timezone_get())를 따른다.
     *
     * @param int $timestamp 변환할 값
     */
    public static function datetime(int $timestamp) : string{
        return date("Y년 m월 d일 G시 i분 s초", $timestamp);
    }

    /**
     * 주어진 unix timestamp를 "2021년 01월 01일"와 같은 한글로 변환
     *
     * @param int $timestamp 변환할 값
     */
    public static function date(int $timestamp) : string{
        return date("Y년 m월 d일", $timestamp);
    }

    /**
     * 주어진 unix timestamp를 "12시 34분 56초"와 같은 한글로 변환
     *
     * @param int $timestamp 변환할 값
     */
    public static function time(int $timestamp) : string{
        return date("G시 i분 s초", $timestamp);
    }

    /**
     * 주어진 초(second)를 "1주 2일 12시간 34분 56초"와 같은 한글로 변환
     *
     * 0이 아닌 단위만 나타내며, 0초는 "0초", 음수는 "-1분 5초"처럼 맨 앞에 "-"를 붙여 변환한다.
     *
     * @param int  $timestamp 변환할 값 (초)
     * @param bool $space     단위 사이에 공백을 넣을지 여부
     */
    public static function period(int $timestamp, bool $space = true) : string{
        return self::join(
            self::split($timestamp, [
                604800 => "주",
                86400 => "일",
                3600 => "시간",
                60 => "분",
            ], "초"),
            $timestamp < 0,
            null,
            $space
        );
    }

    /**
     * $number를 큰 단위부터 나누어 "몫+단위" 목록으로 만든다. 0인 단위는 건너뛰고, 남은 값은 $lastUnit과 함께 마지막에 붙인다.
     * 전체가 0이면 "0".$lastUnit 하나만 반환한다.
     * 부호는 여기서 붙이지 않는다. (몫과 나머지에만 abs()를 적용하므로 PHP_INT_MIN도 오버플로 없이 처리된다)
     *
     * @param array<int, string> $units 큰 단위부터 나열한 [나눌 값 => 단위]
     *
     * @return list<string>
     */
    private static function split(int $number, array $units, string $lastUnit) : array{
        $parts = [];
        foreach($units as $div => $str){
            $quotient = intdiv($number, $div);
            if($quotient !== 0){
                $parts[] = abs($quotient) . $str;
                $number %= $div;
            }
        }
        if($parts === [] || $number !== 0){
            $parts[] = abs($number) . $lastUnit;
        }
        return $parts;
    }

    /** @param list<string> $parts */
    private static function join(array $parts, bool $negative, ?string $unit, bool $space) : string{
        if($unit !== null && $unit !== ""){
            $parts[] = $unit;
        }
        return ($negative ? "-" : "") . implode($space ? " " : "", $parts);
    }
}
