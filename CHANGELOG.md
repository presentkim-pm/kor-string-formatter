# Changelog

## 2.0.0 (2026-11-27)

The Poggit virion version for the original [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 5.x is kept on the
[`legacy/pmmp-5.44`](https://github.com/presentkim-pm/kor-string-formatter/tree/legacy/pmmp-5.44) branch.

### ⚠️ Breaking changes
- Requires **PHP 8.4+** and no longer depends on PocketMine-MP at all: it is a plain PHP library, usable in any project.
- Distributed with Composer: the Poggit virion files (`.poggit.yml`, `virion.yml`) are removed.

### ✨ Features
- Negative values are supported by `KorStr::number()` and `KorStr::period()`, with a leading `-`
  (`-12345` becomes `-1만 2345`). They used to be printed as a plain integer / `-N초` without any grouping.
- `KorStr::number()` ignores an empty `$unit` (it used to leave a trailing space).
- Added the missing README, LICENSE and `composer.json`.

### 🐛 Fixes
- `PHP_INT_MIN` is handled without integer overflow.

### 🧪 Tests
- PHPUnit tests (units, zero, skipped units, spacing, negatives, `PHP_INT_MAX` / `PHP_INT_MIN`, time zones) and GitHub
  Actions CI on PHP 8.4 / 8.5.
