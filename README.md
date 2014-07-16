PHP Swagger Generator
=====================
Version v0.1.0

Copyright &copy; 2014 Martijn van der Lee (http://martijn.vanderlee.com).
MIT Open Source license applies.

PHP tool to generate Swagger API documentation files from comments in PHP source
code or most other programming languages.

PHPSwaggerGen takes a number of files or text fragments as source, scans these
sources for lines starting with a particular prefix (`@rest\` by default) and
parses the command on the line.

Release notes
-------------
This is an early preview release. It generates working Swagger JSON files and is
able to offer most of the specification as comment commands, but lacks a few
significant features.

*	Most notably, any and all OAuth2 authorization commands are missing; I
	simply don't need these myself.
*	It is currently also unable to handle multi-line comments. All the
	arguments for a command must be on a single line. I have a few ideas on how
	to deal with this, but nothing has been implemented so far.
*	model's `subTypes` and `discriminator` and not yet supported.
*	responseMessages's `responseModel` is not yet supported.
*	Support for the `File` type is severely lacking at best.
*	Also notable is the lacking of documentation and proper unittesting. I am
	well aware of the irony.

Consider all of the above on the "to do some day" list. I you need a particular
feature, just submit an issue and I'll prioritize it.

Mapping to Swagger specifications
---------------------------------
PHPSwaggerGen is based on the 1.2 specification of Swagger. This specification
contains a few ambiguous uses of words. Notably 'api' is used with multiple
incompatible meanings.

To avoid this confusion, I've use the words "api" to mean
the functional collection of endpoints and "endpoint" for actual endpoints.

Swaggers' operations have been renamed to "methods" as this seemed nicer to
me. It might be aliassed as "operation" in the future.

Inheritance
-----------
Swagger documents an API in multiple levels:

* resource (there can be only one)
* * apis
* * * endpoints
* * * * methods
* * * * * parameters
* * * * * errors
* * * models
* * * * properties

You are always working on one of the levels (or "contexts"). By default you start on the
"resource" level, but as soon as you use the `api` command, you'll be working on
the "apis" level. Notice that the order of commands is very important.

To switch levels, you can only use a level directly below the current level or
any level higher up in the tree (though currently no switching back to the
"resource" level; no need to). If you try to use a command belonging to a deeper
level, PHPSwaggerGen will remember those commands and apply them to all deeper
levels; inheritance. For example, if you're at the "resources" level and use the
`error` command, the error you define will be applied to __all__ "method"
levels.

Generally speaking, you can use any command at any time, and it'll do what you
expect it to do.

Multiplicity
------------
Some commands define items which are either optional or can be repeated. You can
specify this multiplicity by adding any of the following to the command:

*	'' (nothing) - One time
*	'?' - Zero or one (a.k.a. "Optional")
*	'+' - Zero or more
*	'+' - One or more

By default (nothing specified), all are considered required.

Primitives
----------
Swagger defines a number of primitive datatypes by specifying their `type` and
`format`. PHPSwaggerGen only knows the primitive datatypes themselves; no need
to specify a type and format independantly.

*	Supported are `integer`, `long`, `float`, `double`, `string`, `byte`,
	`boolean`, `date` and `datetime`. You can specify minimum and maximum values
	in parenthesis like so: `integer(0,100)`.
*	You can also use `array` and `set`, the latter is an array where each
	element must be unique. You can specify the primitive type for the items in
	parenthesis as such: `array(string)`.
*	Also, `file` and `void` are supported as well.

Commands
--------
All parts of a command are separated by spaces; no quotes are necessary or even
allowed. The last argument (usually "description") of each command is basically
everything upto the end of the line, so that one _can_ contain spaces.

### `apiversion {value}` (resource)

### `swaggerversion {value}` (resource)

### `title {value}` (resource)

### `description {value}` (most anything)

### `termsofserviceurl {value}` (resource)

### `contact {value}` (resource)

### `license {name}` (resource)
Some license names are recognized and automatically set the license URL.
Currently only `mit` and `apache 2.0` are recognized. Please submit an issue if
you want some particular license to be added.

### `licenseurl {value}` (resource)

### `basepath {value}` (resource)

### `resourcepath {value}` (resource)

### `notes {value}` (resource)

### `api {name} {description}`

### `endpoint {path} {description}` (api)

### `method {method} {description}` (endpoint)
{method} is `GET`, `POST`, `PUT`, `DELETE` or `PATCH`; any of the HTTP methods.

### `body{multiplicity} {primitive} {name} {description}` (parameter)

### `form{multiplicity} {primitive} {name} {description}` (parameter)

### `header{multiplicity} {primitive} {name} {description}` (parameter)

### `path{multiplicity} {primitive} {name} {description}` (parameter)

### `query{multiplicity} {primitive} {name} {description}` (parameter)
{multiplicy} is empty, `+`, `*` or `?`.

### `error {code} {reason}` (method)

### `errors {code} {code} {...} {code}` (method)

### `model{multiplicity} {name} {description}` (api)
{multiplicy} is either empty or `?`.

### `property {primitive} [name} {description}` (model)

### `default {value}` (parameter/property)

### `items {value}` (parameter/property)

### `deprecated` (method)

### `enum {word} {word} {...} {word}` (parameter/property)
Enumeration of the

### `produces {mime-type} {mime-type} {...} {mime-type}` (api)

### `consumes {mime-type} {mime-type} {...} {mime-type}` (api)

Example
-------
Take a look at the examples in the `test-source` directory.

To do (mostly for my own reference)
-----------------------------------
*	Automated unittests
*	Full documentation (and more readable)
*	Nice index page (use my standard jQuery page?)
*	Pet-store example
*	Resolve "description" issue for parameters
*	Add convenience as needed
*	Add the missing features mentioned above
*	Report to main Swagger site/wiki at some point