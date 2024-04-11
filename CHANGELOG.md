# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## 2.3.22 - 2024-04-11
### Fixed
- Fixed example case.
- PHP code quality improvements.

## 2.3.21 - 2021-01-07
### Fixed
- PR #47; Fix array access PHP8 by daniol.
- Minor fixes for php-unit.

## 2.3.20 - 2019-12-02
### Fixed
- PR #45 fixes #44; PHP Parser fails when parsing @rest\property! by SteenSchutt
- PR #42; `example` command: support for input containing special chars by sserbin.

## 2.3.19 - 2019-01-12
### Changed
- PR #41 Fix for JSON example command with multiple properties by sserbin.

## 2.3.18 - 2017-12-06
### Changed
- PR #32 Separated parsing from compiling parts, by weirdan.

## 2.3.17 - 2017-11-28
### Added
- Support for `additionalProperties` (#31) added by weirdan.

## 2.3.16 - 2017-11-07
### Added
- Support for `allOf` added by weirdan.
### Fixed
- Suppress `exclusive*` when no min/max specified, by weirdan.
- Significant refactoring of type creating by weirdan.

## 2.3.15 - 2017-10-29
### Added
- Added `example` to Response and parameters to fix #22 submitted by oozolii.

## 2.3.14 - 2017-10-22
### Added
- Short-hand JSON like notation for objects (`{...}`) and arrays (`[...]`). 

## 2.3.13 - 2016-07-29
### Fixed
- Passthrough of commands to most recent property of object (#15) submitted by
weirdan.
- Allow `HEAD` and `OPTIONS` methods (#16) submitted by weirdan.

## 2.3.12 - 2016-07-22
### Fixed
- Read-only definitions fix (#14) by petejohnson84.
- Removed hhvm Travis.ci tests due to bug on Travis.ci.

## 2.3.11 - 2017-06-14
### Fixed
- Read-only properties fix (#13) by petejohnson84.
- Fixed issue with preprocessor statements being interpreted as plain text.

## 2.3.10 - 2016-12-17
### Fixed
- Correctly parse parenthesis in object properties. Fixes issue #12 by
ObliviousHarmony.

## 2.3.9 - 2016-11-17
### Fixed
- Supply custom `format` for type `uuid`.
- Improved some `string` type exception messages.

## 2.3.8 - 2016-11-16
### Added
- New string-based builtin type `uuid`.
- Allow model definitions to overwrite builtin types.

## 2.3.7 - 2016-10-23
### Fixed
- Supports referencing a single `definition` (a.k.a. `model`) from within
another `definition`. Fixes issue #10 by tecnom1k3.

## 2.3.6 - 2016-10-15
### Added
- Parameter commands (i.e. `path`, `query?` and `body`) in `Swagger` context to
create global parameter definitions.
- `parameter` command (and alias `param`) in `Operation` context to create
references to global parameter definitions.
- Alternative signature for `response` for references to Response Definitions.
- `description` command in `Schema` context to set/change the description.
- `title` command in `Schema` context to set/change the title.
- `StatementException` added. Can be thrown during the generator phase, after
parsing the input. Contains a `getStatement()` method which returns a
`Statement` object with which you can access the public `getFile()` (if
applicable) and `getLine()` methods.

### Changed
- Removed the `type` argument from the `definition` command in the Swagger
context; it's always a Schema. Note that this is a backwards incompatible
change!

### Deprecated
- Removed the `define` command in the `Swagger` context as it was in conflict
with the `define` command in the preprocessor. Replace use of this command with
the previous available aliasses `definition` or `model`. Since the `define`
command was blocked by the preprocessor context, this deprecation shouldn't
affect users.
- Merged the `\SwaggerGen\Parser\Php\Statement` class (and tests) into the
`\SwaggerGen\Statement` class for universal file/line support. This shouldn't
affect average users.
