# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

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