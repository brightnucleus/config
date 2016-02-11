# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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

[0.1.5]: https://github.com/brightnucleus/config/compare/v0.1.4...v0.1.5
[0.1.4]: https://github.com/brightnucleus/config/compare/v0.1.3...v0.1.4
[0.1.3]: https://github.com/brightnucleus/config/compare/v0.1.2...v0.1.3
[0.1.2]: https://github.com/brightnucleus/config/compare/v0.1.1...v0.1.2
[0.1.1]: https://github.com/brightnucleus/config/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/brightnucleus/config/compare/v0.0.0...v0.1.0
