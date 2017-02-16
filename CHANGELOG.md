# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [0.4.10] - 2017-02-16
### Fixed
- Fix Coveralls integration.

## [0.4.9] - 2017-02-16
### Added
- Added several checks that can be run through Composer scripts.
- Added Travis CI configuration.

## [0.4.8] - 2016-08-06
### Added
- Added `ConfigTrait::getConfigCallable()` to get a callable and immmediately execute it to get the resultant value.
- Added corresponding unit test.

## [0.4.7] - 2016-07-22
### Fixed
- Fixed the way arrays were merged recursively.
- Added regression test.

## [0.4.6] - 2016-07-22
### Added
- Added `ConfigFactory::merge()` to merge several Config files into one.
- Added corresponding unit test.

## [0.4.5] - 2016-07-05
### Added
- Added `ConfigFactory::createSubConfig()` to quickly create a new config at a sublevel.
- Added corresponding unit test.

## [0.4.4] - 2016-07-05
### Changed
- Extracted the cache retrieval in `ConfigFactory` into its own method.

## [0.4.3] - 2016-07-05
### Added
- Added file caching to CreateFactory.
- Added unit tests to make sure the caching works.

## [0.4.2] - 2016-06-09
### Added
- Added trait functionality to load a default config file.
- Added corresponding entry in the README.md file.

## [0.4.1] - 2016-06-08
### Removed
- Removed `beberlei/assert` and all assert checks for now, until a better replacement has been found.

## [0.4.0] - 2016-04-28
### Added
- Added extensible loader framework that includes `PHPLoader` and can be extended by other packages.

### Changed
- Changed the extensions that are being thrown to more specific ones. They types are extensions of the ones that were previously used, but some exception messages have changed.
- Generic `Config` class now uses the loader framework instead of reading the data on its own.

## [0.3.1] - 2016-04-05
### Changed
- Update Composer dependencies.

## [0.3.0] - 2016-04-04
### Added
- Added `ConfigFactory`.

### Changed
- Changed licensing from GPL2 to MIT.

## [0.2.8] - 2016-03-28
### Changed
- Bumped [PHP Composter PHPCS PSR-2](https://github.com/php-composter/php-composter-phpcs-psr2) version.

## [0.2.7] - 2016-03-28
### Changed
- Switched from a custom `pre-commit` script to the [PHP Composter PHPCS PSR-2](https://github.com/php-composter/php-composter-phpcs-psr2) package.

## [0.2.6] - 2016-03-22
### Fixed
- Switch `beberlei/assert` back to official branch. Issue [#138](https://github.com/beberlei/assert/issues/138) has been fixed with v2.5.

## [0.2.5] - 2016-03-04
### Fixed
- Switch `beberlei/assert` to own fork until [#138](https://github.com/beberlei/assert/issues/138) has been fixed.

## [0.2.4] - 2016-02-26
### Added
- Better documentation in `README.md`.

### Fixed
- Corrected type hints for variadic methods.

## [0.2.3] - 2016-02-19
### Fixed
- Remove test cases that were not needed anymore due to the addition of the `$_` argument in v0.2.2.
- Remove const array for truthy values and replace by local variable, to avoid PHP5.6+ requirement.

## [0.2.2] - 2016-02-18
### Fixed
- Add dummy variables in methods with a variable number of arguments to fix PHPStorm inspections.
- Fix case in `reduceToSubKey()` method.

## [0.2.1] - 2016-02-18
### Fixed
- Bumped version requirement for `brightnucleus/exceptions` to v0.2+.

## [0.2.0] - 2016-02-17
### Added
- Added `getSubConfig()` to extract the subtree at a specific level & key.
- Added corresponding tests.

## [0.1.12] - 2016-02-17
### Fixed
- Updated `brightnucleus/exceptions` dependency.

## [0.1.11] - 2016-02-17
### Fixed
- Lowered version requirement for `symfony/options-resolver`.

## [0.1.10] - 2016-02-16
### Added
- Added `brightnucleus/exceptions` to have coherentexceptions across all Bright Nucleus packages.

## [0.1.9] - 2016-02-16
### Added
- The `beberlei/assert` package has been added as a development dependency.
- Several assertions have been added to check the arguments passed in to the methods.
- A `pre-commit` hook has been added to run unit tests and validate code standards.

### Fixed
- Methods have been rearranged.
- A few code style tweaks have been made to adhere to PHPCS PSR-2.

## [0.1.8] - 2016-02-11
### Added
- The `ConfigTrait::processConfig()` method now accepts one or more additional parameters (can be delimited strings) to start with a Config at a specific level. Useful to include different `vendor\package` levels in a single merged Config file.

## [0.1.7] - 2016-02-11
### Fixed
- The `hasKey()` method doesn't throw an exception, returns `false` instead.

## [0.1.6] - 2016-02-11
### Added
- The `has*` & `get*` methods now support keys with delimiters. The delimiters are set via the Config constructor.
- Tests for the above.

## [0.1.5] - 2016-02-11
### Added
- `ConfigTrait::hasConfigKey()` and `ConfigTrait::getConfigKey()` now support a list of strings to fetch a value from several levels deep.
- Tests for the above.

## [0.1.4] - 2016-02-11
### Added
- `AbstractConfig:getAll()` and `ConfigTrait::getConfigArray()`.
- `hasKey()` and `getKey()` now support a list of strings to fetch a value from several levels deep.
- Tests for the above.

### Fixed
- PHP requirement for unit tests was lowered from 5.6 to 5.4.

## [0.1.3] - 2016-02-10
### Added
- Badges in README.md

### Fixed
- Update Composer to use PHPUnit 4 to reduce PHP version requirements.
- Fix several minor code quality issues.

## [0.1.2] - 2016-02-01
### Added
- ConfigTrait.
- Tests for ConfigTrait.

## [0.1.1] - 2016-01-29
### Added
- Tests for schema requirements.

### Fixed
- Don't instantiate `OptionsResolver` if not needed.
- Formatting tweaks

## [0.1.0] - 2016-01-29
### Added
- Initial release to GitHub.

[0.4.10]: https://github.com/brightnucleus/config/compare/v0.4.9...v0.4.10
[0.4.9]: https://github.com/brightnucleus/config/compare/v0.4.8...v0.4.9
[0.4.8]: https://github.com/brightnucleus/config/compare/v0.4.7...v0.4.8
[0.4.7]: https://github.com/brightnucleus/config/compare/v0.4.6...v0.4.7
[0.4.6]: https://github.com/brightnucleus/config/compare/v0.4.5...v0.4.6
[0.4.5]: https://github.com/brightnucleus/config/compare/v0.4.4...v0.4.5
[0.4.4]: https://github.com/brightnucleus/config/compare/v0.4.3...v0.4.4
[0.4.3]: https://github.com/brightnucleus/config/compare/v0.4.2...v0.4.3
[0.4.2]: https://github.com/brightnucleus/config/compare/v0.4.1...v0.4.2
[0.4.1]: https://github.com/brightnucleus/config/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/brightnucleus/config/compare/v0.3.1...v0.4.0
[0.3.1]: https://github.com/brightnucleus/config/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/brightnucleus/config/compare/v0.2.8...v0.3.0
[0.2.8]: https://github.com/brightnucleus/config/compare/v0.2.7...v0.2.8
[0.2.7]: https://github.com/brightnucleus/config/compare/v0.2.6...v0.2.7
[0.2.6]: https://github.com/brightnucleus/config/compare/v0.2.5...v0.2.6
[0.2.5]: https://github.com/brightnucleus/config/compare/v0.2.4...v0.2.5
[0.2.4]: https://github.com/brightnucleus/config/compare/v0.2.3...v0.2.4
[0.2.3]: https://github.com/brightnucleus/config/compare/v0.2.2...v0.2.3
[0.2.2]: https://github.com/brightnucleus/config/compare/v0.2.1...v0.2.2
[0.2.1]: https://github.com/brightnucleus/config/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/brightnucleus/config/compare/v0.1.12...v0.2.0
[0.1.12]: https://github.com/brightnucleus/config/compare/v0.1.11...v0.1.12
[0.1.11]: https://github.com/brightnucleus/config/compare/v0.1.10...v0.1.11
[0.1.10]: https://github.com/brightnucleus/config/compare/v0.1.9...v0.1.10
[0.1.9]: https://github.com/brightnucleus/config/compare/v0.1.8...v0.1.9
[0.1.8]: https://github.com/brightnucleus/config/compare/v0.1.7...v0.1.8
[0.1.7]: https://github.com/brightnucleus/config/compare/v0.1.6...v0.1.7
[0.1.6]: https://github.com/brightnucleus/config/compare/v0.1.5...v0.1.6
[0.1.5]: https://github.com/brightnucleus/config/compare/v0.1.4...v0.1.5
[0.1.4]: https://github.com/brightnucleus/config/compare/v0.1.3...v0.1.4
[0.1.3]: https://github.com/brightnucleus/config/compare/v0.1.2...v0.1.3
[0.1.2]: https://github.com/brightnucleus/config/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/brightnucleus/config/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/brightnucleus/config/compare/v0.0.0...v0.1.0
