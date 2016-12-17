# Todo
## Tests
*	Reference response definition
*	Explicitely reference response definition

## Document
*	`x-...` on AbstractObject context

## Code	
*	Internal redesign of `handleCommand` Do a `handleStatement` instead.
*	Command to move to `swagger` context; `goto`?
*	Aliases for force global parameters and/or responses.
*	Exception; record statement source
*	Add full Schema-level type support for response headers.
*	Options to enable/disable comment types.
*	Option to specify comment command prefix. `rest` or `@rest\`.
*	Ordering options for tags and/or paths and/or operations; sort according to list for tags
*	Parse and reference functions
*	Rethink pre-function comment(s); add to function/method or class?
*	Type alias/extension system
*	Command aliassing system.
*	Command line interface. Netbeans integration.
*	Use different mechanism for preprocessor: `#` or such prefix
*	Standardize the Parser interface; `parseFile()`, `parseText()`, defines
*	Add text preprocessor

## Non-OpenAPI features
*	`@rest\type definition typename` to define new "builtin" types on the fly.
*	Global parameter definitions that are applied to all operations. This mostly
	applies to `query` parameters. Perhaps `globalquery` et al.
*	Add more builtin regex-based types; `ipv4`, `ipv6`, `ip` (any kind), `url`,
	`uri`, `ftp`, `http`, `https`, `email`

## Swagger
*	Full Type support in Swagger\Header object
*	Use (optional) Namespaces in `@see` and `@uses`
*	Set type (array of enumerated strings; can force unique?)
*	License: full/formatted names
*	Date(-time) format helpers; if no timezone, add 'Z'. Use PHP Date parser.
*	Support object `additionalProperties` and `allOf`
*	Shortcut "get", "put", etc. operation methods as proper commands.
*	Force correct defaults on models. [See issue](https://github.com/swagger-api/swagger-ui/issues/2436)
*	Implement `examples` in `Response`.
*	Implement `required` in `Schema` for object properties. (JSON Schema, p.12)
*	Add command aliasses `tag`, `scheme`, `consumes` and `produces` in `Operations`.

## Quality
*	Parsers; pass state object instead of keeping state in parser objects properties.
*	PHP: Cache previously parsed files; do not re-parse?
*	PSR-* compliance
*	Document comment structure in classes; before/in/after class/method/function
*	Scrutinizer perfection

## Validations
*	`body` and `formData` Parameters cannot exist in single Operation.
*	`path` Parameters must reference part of Path.
*	For `oauth2` security, check scopes in `require` and vice versa.