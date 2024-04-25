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

namespace kim\present\korstringfomatter;

use function count;
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
     * @param int         $number 변환할 값
     * @param string|null $unit   뒤에 붙일 단위, null이면 붙이지 않음
     * @param bool        $space  단위 사이에 공백을 넣을지 여부
     *
     * @return string
     */
    public static function number(int $number, ?string $unit = null, bool $space = true) : string{
        $return = [];
        foreach([
                    10000000000000000 => "경",
                    1000000000000 => "조",
                    100000000 => "억",
                    10000 => "만",
                ] as $div => $str
        ){
            if($number >= $div){
                $return[] = intdiv($number, $div) . $str;
                $number %= $div;
            }
        }
        if(count($return) === 0 || $number > 0){
            $return[] = $number;
        }
        if($unit !== null){
            $return[] = $unit;
        }
        return implode($space ? " " : "", $return);
    }

    /**
     * 주어진 unix timestamp를 "2021년 01월 01일 12시 34분 56초"와 같은 한글로 변환
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
     * 주어진 unix timestamp을 "1일 12시간 34분 56초"와 같은 한글로 변환
     *
     * @param int  $timestamp 변환할 값
     * @param bool $space     단위 사이에 공백을 넣을지 여부
     */
    public static function period(int $timestamp, bool $space = true) : string{
        $result = [];
        foreach([
                    604800 => "주",
                    86400 => "일",
                    3600 => "시간",
                    60 => "분",
                ] as $div => $str
        ){
            if($timestamp >= $div){
                $result[] = intdiv($timestamp, $div) . $str;
                $timestamp %= $div;
            }
        }
        if($result === [] || $timestamp > 0){
            $result[] = $timestamp . "초";
        }
        return implode($space ? " " : "", $result);
    }
}
