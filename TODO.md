# Todo
## Code
*	Add full Schema-level type support for response headers.
*	Options to enable/disable comment types.
*	Option to specify comment command prefix. "rest" or "@rest\".
*	Ordering options for tags and/or paths and/or operations; sort according to list for tags
*	Parse and reference functions
*	Rethink pre-function comment(s); add to function/method or class?
*	Type alias/extension system
*	Command aliassing system.
*	Command line interface. Netbeans integration.
*	Use different mechanism for preprocessor: # or such prefix

## Swagger
*	Full Type support in Swagger\Header object
*	Use (optional) Namespaces in @see and @uses
*	Set type (array of enumerated strings; can force unique?)
*	License: full/formatted names
*	Date(-time) format helpers; if no timezone, add 'Z'. Use PHP Date parser.
*	Support object "additionalProperties" and "allOf"
*	Shortcut "get", "put", etc. operation methods as proper commands.

## Quality
*	Parsers; pass state object instead of keeping state in parser objects properties.
*	PHP: Cache previously parsed files; do not re-parse?
*	Unittests and Travis-CI integration.
*	PSR-* compliance

## Validations
*	`body` and `formData` Parameters cannot exist in single Operation.
*	`path` Parameters must reference part of Path.
*	Check required `security` objects exist in `toArray`.