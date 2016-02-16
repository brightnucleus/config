# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
