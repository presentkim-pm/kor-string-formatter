<?php

declare(strict_types=1);

namespace kim\present\formatter\tests;

use kim\present\formatter\KorStr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class KorStrTest extends TestCase{

    private string $previousTimezone;

    protected function setUp() : void{
        $this->previousTimezone = date_default_timezone_get();
        date_default_timezone_set("UTC");
    }

    protected function tearDown() : void{
        date_default_timezone_set($this->previousTimezone);
    }

    /** @return iterable<string, array{int, ?string, bool, string}> */
    public static function numbers() : iterable{
        yield "zero" => [0, null, true, "0"];
        yield "small" => [7, null, true, "7"];
        yield "below man" => [9999, null, true, "9999"];
        yield "exactly man" => [10000, null, true, "1만"];
        yield "man with rest" => [10001, null, true, "1만 1"];
        yield "man and rest" => [12345, null, true, "1만 2345"];
        yield "exactly eok" => [100000000, null, true, "1억"];
        yield "eok skipping man" => [100000007, null, true, "1억 7"];
        yield "eok man rest" => [123456789, null, true, "1억 2345만 6789"];
        yield "jo" => [1000000000000, null, true, "1조"];
        yield "jo eok man rest" => [1234567890123, null, true, "1조 2345억 6789만 123"];
        yield "gyeong" => [10000000000000000, null, true, "1경"];
        yield "gyeong all" => [12345678901234567, null, true, "1경 2345조 6789억 123만 4567"];
        yield "int max" => [PHP_INT_MAX, null, true, "922경 3372조 368억 5477만 5807"];
        yield "with unit" => [12345, "개", true, "1만 2345 개"];
        yield "unit on zero" => [0, "개", true, "0 개"];
        yield "no space" => [12345, "개", false, "1만2345개"];
        yield "no space no unit" => [100000007, null, false, "1억7"];
        yield "empty unit is ignored" => [5, "", true, "5"];
        yield "negative" => [-5, null, true, "-5"];
        yield "negative big" => [-123456789, "원", true, "-1억 2345만 6789 원"];
        yield "negative exact" => [-10000, null, true, "-1만"];
        yield "int min" => [PHP_INT_MIN, null, true, "-922경 3372조 368억 5477만 5808"];
    }

    #[DataProvider('numbers')]
    public function testNumber(int $number, ?string $unit, bool $space, string $expected) : void{
        self::assertSame($expected, KorStr::number($number, $unit, $space));
    }

    public function testNumberDefaultsKeepUnitSeparatedBySpace() : void{
        self::assertSame("3 명", KorStr::number(3, "명"));
    }

    /** @return iterable<string, array{int, bool, string}> */
    public static function periods() : iterable{
        yield "zero" => [0, true, "0초"];
        yield "seconds" => [59, true, "59초"];
        yield "one minute" => [60, true, "1분"];
        yield "minute and seconds" => [61, true, "1분 1초"];
        yield "one hour" => [3600, true, "1시간"];
        yield "hour and seconds skipping minutes" => [3601, true, "1시간 1초"];
        yield "one day" => [86400, true, "1일"];
        yield "day hour minute second" => [86400 + 12 * 3600 + 34 * 60 + 56, true, "1일 12시간 34분 56초"];
        yield "one week" => [604800, true, "1주"];
        yield "weeks days" => [604800 * 3 + 86400 * 2, true, "3주 2일"];
        yield "no space" => [86400 + 12 * 3600 + 34 * 60 + 56, false, "1일12시간34분56초"];
        yield "negative" => [-61, true, "-1분 1초"];
        yield "negative seconds" => [-5, true, "-5초"];
    }

    #[DataProvider('periods')]
    public function testPeriod(int $seconds, bool $space, string $expected) : void{
        self::assertSame($expected, KorStr::period($seconds, $space));
    }

    public function testPeriodOfLargeValueDoesNotOverflow() : void{
        self::assertStringEndsWith("초", KorStr::period(PHP_INT_MAX));
        self::assertStringStartsWith("-", KorStr::period(PHP_INT_MIN));
    }

    public function testDatetime() : void{
        $timestamp = gmmktime(12, 34, 56, 1, 1, 2021);
        self::assertSame("2021년 01월 01일 12시 34분 56초", KorStr::datetime($timestamp));
        self::assertSame("2021년 01월 01일", KorStr::date($timestamp));
        self::assertSame("12시 34분 56초", KorStr::time($timestamp));
    }

    public function testHourHasNoLeadingZeroButMinutesAndSecondsDo() : void{
        $timestamp = gmmktime(1, 2, 3, 12, 9, 2022);
        self::assertSame("2022년 12월 09일 1시 02분 03초", KorStr::datetime($timestamp));
        self::assertSame("1시 02분 03초", KorStr::time($timestamp));
    }

    public function testMidnightAndEpoch() : void{
        self::assertSame("1970년 01월 01일 0시 00분 00초", KorStr::datetime(0));
    }

    public function testFollowsDefaultTimezone() : void{
        date_default_timezone_set("Asia/Seoul");
        self::assertSame("1970년 01월 01일 9시 00분 00초", KorStr::datetime(0));
    }

    public function testNotInstantiable() : void{
        $constructor = (new \ReflectionClass(KorStr::class))->getConstructor();
        self::assertNotNull($constructor);
        self::assertTrue($constructor->isPrivate());
    }
}
