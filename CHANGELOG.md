# Assets Autoversioning Changelog

## 3.0.0 - 2026-08-06

### Added

- Craft CMS 5 compatibility.

### Changed

- Requires Craft CMS >= 5.0 and PHP >= 8.2.
- Version is now derived from the Git tag.
- The Twig extension is now only registered for web requests.

### Fixed

- Each file now falls back to its own modification time instead of reusing the first file's timestamp for every asset in a template ([#4](https://github.com/codemonauts/craft-asset-autoversioning/issues/4)).
- `useQueryParam` no longer produces a double `?` when the asset path already contains a query string.
- Hardened build-ID file resolution against the `bool|string|null` return type of `App::parseEnv()`.

### Internal

- Added `declare(strict_types=1)` across the plugin and injected the settings into the Twig extension instead of sharing them through a static property.
- Exclude test fixtures from the distributed package via `.gitattributes`.
- Added `changelogUrl` so the Plugin Store surfaces release notes.

## 2.0.0 - 2022-05-04

### Added

- Craft CMS 4 compatibility.
- Settings page.
- Switch to activate/deactivate versioning. Can be set via environment variable.
- The file with the build ID to use as version is now configurable.
- Use query parameter instead of file suffix when adding the version to an asset.

### Changed

- Requires Craft CMS >= 4.0

## 1.0.3 - 2019-04-06

### Changed

- New icon
- Updated documentation

## 1.0.2 - 2019-01-09

### Removed

- Removed version number

## 1.0.1 - 2018-12-19

### Changed

- Fix version number

## 1.0.0 - 2018-12-19

### Added

- Initial release
