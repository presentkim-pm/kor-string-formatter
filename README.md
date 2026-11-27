<!-- PROJECT BADGES -->
<div align="center">

[![Packagist][packagist-badge]][packagist-url]
[![CI][ci-badge]][ci-url]
[![Stars][stars-badge]][stars-url]
[![License][license-badge]][license-url]

</div>


<!-- PROJECT LOGO -->
<br />
<div align="center">
  <h3>kor-string-formatter</h3>
  <p align="center">
    Format numbers, dates and durations as Korean text.

[Packagist][packagist-url] · [Changelog](CHANGELOG.md) · [Report a bug][issues-url] · [Request a feature][issues-url]

  </p>
</div>

```php
KorStr::number(123456789, "원"); // "1억 2345만 6789 원"
KorStr::period(90061);           // "1일 1시간 1분 1초"
KorStr::date($timestamp);        // "2021년 01월 01일"
```

-----

## 🌟 Why kor-string-formatter?

- **Korean number grouping** : `경 / 조 / 억 / 만` units, zero groups skipped (`1억 7`, not `1억 0만 7`)
- **Durations** : `주 / 일 / 시간 / 분 / 초`, zero parts skipped
- **Dates and times** : `년 월 일 시 분 초` in the PHP default time zone
- **Dependency free** : plain PHP, no PocketMine-MP needed
- **Tested** : on PHP 8.4 / 8.5

-----

## 📦 Installation

```sh
composer require presentkim/kor-string-formatter
```

| Requirement | Version          |
|-------------|------------------|
| PHP         | **8.4** or newer |

It is a plain PHP library, so it works in any project. For [axolotl-pm/PocketMine-MP][axolotl-url] plugins
(a fork of PocketMine-MP 5) just require it from the plugin's own `composer.json`.

> The Poggit virion for the original PocketMine-MP 5.44 and older is kept on the
> [`legacy/pmmp-5.44`][legacy-url] branch (no longer maintained).

-----

## 🚀 Quick start

```php
use kim\present\formatter\KorStr;

KorStr::number(12345);                // "1만 2345"
KorStr::number(12345, "개");          // "1만 2345 개"
KorStr::number(12345, "개", false);   // "1만2345개"
KorStr::number(100000007);            // "1억 7"
KorStr::number(-123456789, "원");     // "-1억 2345만 6789 원"

KorStr::period(61);                   // "1분 1초"
KorStr::period(86400 + 12 * 3600);    // "1일 12시간"
KorStr::period(604800 * 2, false);    // "2주"

KorStr::datetime(1609504496);         // "2021년 01월 01일 12시 34분 56초" (UTC)
KorStr::date(1609504496);             // "2021년 01월 01일"
KorStr::time(1609504496);             // "12시 34분 56초"
```

-----

## 📖 API reference

| Method                                                                   | Description                                             |
|--------------------------------------------------------------------------|---------------------------------------------------------|
| `KorStr::number(int $number, ?string $unit = null, bool $space = true)`  | `12345` to `1만 2345`, with an optional trailing unit   |
| `KorStr::period(int $seconds, bool $space = true)`                       | A number of seconds to `1주 2일 3시간 4분 5초`          |
| `KorStr::datetime(int $timestamp)`                                       | A unix timestamp to `2021년 01월 01일 12시 34분 56초`   |
| `KorStr::date(int $timestamp)`                                           | A unix timestamp to `2021년 01월 01일`                  |
| `KorStr::time(int $timestamp)`                                           | A unix timestamp to `12시 34분 56초`                    |

Notes:

- `0` is `"0"` (`"0초"` for `period()`). Negative values get a leading `-`.
- The hour is not zero padded (`9시`), minutes and seconds are (`05분`), as PHP's `G` / `i` / `s` formats.
- `datetime()`, `date()` and `time()` use the PHP default time zone (`date_default_timezone_get()`).
- `$unit` is separated from the number by a space unless `$space` is `false`. An empty `$unit` is ignored.

-----

## 🧪 Development

```sh
composer install
composer test
```

-----

## 📄 License

Distributed under the **MIT** License. See [LICENSE][license-url] for more information.


[packagist-badge]: https://img.shields.io/packagist/v/presentkim/kor-string-formatter.svg?style=for-the-badge

[packagist-url]: https://packagist.org/packages/presentkim/kor-string-formatter

[ci-badge]: https://img.shields.io/github/actions/workflow/status/presentkim-pm/kor-string-formatter/ci.yml?branch=main&style=for-the-badge

[ci-url]: https://github.com/presentkim-pm/kor-string-formatter/actions/workflows/ci.yml

[stars-badge]: https://img.shields.io/github/stars/presentkim-pm/kor-string-formatter.svg?style=for-the-badge

[license-badge]: https://img.shields.io/github/license/presentkim-pm/kor-string-formatter.svg?style=for-the-badge

[stars-url]: https://github.com/presentkim-pm/kor-string-formatter/stargazers

[issues-url]: https://github.com/presentkim-pm/kor-string-formatter/issues

[license-url]: https://github.com/presentkim-pm/kor-string-formatter/blob/main/LICENSE

[legacy-url]: https://github.com/presentkim-pm/kor-string-formatter/tree/legacy/pmmp-5.44

[axolotl-url]: https://github.com/axolotl-pm/PocketMine-MP
