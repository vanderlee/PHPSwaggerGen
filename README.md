SwaggerGen
==========
Version v2.0-beta-1

Copyright &copy; 2014-2015 Martijn van der Lee (http://toyls.com).

MIT Open Source license applies.

Introduction
------------
This is an early Beta version of SwaggerGen 2.0, a complete rewrite of
SwaggerGen for Swagger-spec 2.0 support and improved quality overall.

As befits a beta release, this code should be runnable and usable, but is
likely to contain bugs and may be subject to significant changes.
Also, documentation is largely lacking; note all the `todo` statements.
Also note the large To-do list at the bottom; there is still plenty to do.

Installation
------------
This library should be PSR-4 compatible, so you should be able to use it in any
package manager (though no package manager will be supported until the code has
stabilized).

Requires PHP 5.4 or greater.

PHP 5.3 is supported as long as no more recent features are necessary.
There is no guarantee SwaggerGen will continue to work on PHP 5.3 in the future.



Syntax
======
You can use both proper PHPDoc comments and normal comments.
All comments have to be prefixed with `@rest\` as such:

	/**
     * @rest\title Example API
     */

Or multiline comments:

	/* @rest\endpoint /words Text Manipulate text in various ways
	*/

Or single line comments:

	// @rest\operation GET Get an array of individual words in a sentence.





Get started quick
=================
TODO: Short walkthrough





Contexts and commands
=====================

Swagger (root)
--------------
Represents the entire API documentation.
This is the initial context for commands.

#### Commands
*	**`title` *text ...***
	Set the API title.
	&#x27a4;Info
*	**`description` *text ...***
	Set a description for the API.
	&#x27a4;Info
*	**`schemes` *scheme1 [scheme2] ... [schemeN]***
	Adds protocol schemes. E.g. "http" or "https".
*	**`consumes` *mime1 [mime2] ... [mimeN]***
	Adds mime types that the API is able to understand. E.g.
	"application/json",  "multipart/form-data" or
	"application/x-www-form-urlencoded".
*	**`produces` *mime1 [mime2] ... [mimeN]***
	Adds mime types that the API is able to produce. E.g. "application/xml" or
	"application/json".
*	**`define` *type name***
	Start definition of a Schema (type is "params" or "parameters"), using the
	reference name specified.
	&#x27a4;Schema.
*	**`endpoint` */path [tag] [description ...]***
	Create an endpoint using the /path.
	If tag is set, the endpoint will be assigned to the tag group of that name.
	If a description is set, the description of the group will be set
	accordingly.
	&#x27a4;Path



Path
----
Represents a URL endpoint or Path.

#### Commands
*	**`operation` *method [summary ...]***
	Add a new operation to the most recently specified endpoint.
	Method can be any valid HTTP method; "get", "put", "post", "delete",
	"options", "head", "patch".
	&#x27a4;Operation
*	**`description` *text ...***
	If a tag exists, sets the description for the tag. Otherwise pass along to
	the most recent context that can handle a description.
	&#x27a4;Tag



Parameters
----------

### boolean (bool)
A true/false choice.

	type=default

*	type: `boolean` or `bool`.
*	default: `true`, `false`, 1 (true) or 0 (false).

#### Commands
*	**`default` *value*** Set the default value.

#### Examples
*	**`boolean`** A basic boolean.
*	**`bool=true`** A boolean, default to true.


### int32 (integer, int), int64 (long)
Represents numbers without decimals.

	type[0,>=default

*	type: `integer`, `int`, `int32`, `long` or `int64`.
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` or `max` values means infinity.
*	default: any valid integer.

#### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.
*	**`step` *value*** Set the stepsize between numbers.

#### Examples
*	**`int`** 32-bit integer without a default or limited range.
*	**`long<,0>`** 64-bit negative integers only.
*	**`integer[0,>=100`** 32-bit positive integer or zero, default to 100.


### float, double
Represents floating point numbers (with decimals).

	type[0,>=default

*	type: `float` or `double`
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` or `max` values means infinity.
*	default: any valid integer.

#### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.
*	**`step` *value*** Set the stepsize between numbers.

#### Examples
*	**`float`** 32-bit floating point number without a default or limited range.
*	**`double<,1>`** 64-bit floating point numbers upto (but not including) 1.
*	**`float<0,>=0.1`** 32-bit positive numbers, excluding 0, default to 0.1.


### string, byte, binary, password
Represents a text.

	type(pattern)[0,>=default

*	type: `string` or `binary`,
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` value means zero.
	Empty `max` value means infinity.
*	default: any valid text not containing whitespace.

#### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.

#### Examples
*	**`string`** A simple text field.
*	**`string[,256>`=red ** A text of at most 255 characters, default to "red".
*	**`binary[1,8]`** Upto 8 binary digits, requiring atleast one.


### date, date-time (datetime)
Special type of string which is limited to dates only

	type=default

*	type: `date`, `date-time` or `datetime`,
*	default: Any valid RFC3339 full-date or date-time.

#### Commands
*	**`default` *date*** Set the default value.

#### Examples
*	**`date`** A simple date
*	**`datetime=2015-12-31T12:34:56Z`** Date and time set to a default without
	a timezone offset.
*	**`datetime=2015-12-31T12:34:56.001+01:00`** Date and time set to a default
	value with fractional seconds and a timezone offset.


### enum
Special type of string which is limited to one of a number of predefined values.

	enum(value1,value1,...,valueN)=default

*	values: any text not containing whitespace or commas.
*	default: any of the specified texts.

#### Commands
See string.

#### Examples
*	**`enum(red,green,blue)=red`** A string containing either "red", "green" or
	"blue", default to "red".



Operation
---------
TODO



Error
-----
TODO



Info
----
TODO



Contact
-------
TODO



License
-------
TODO



Response
--------
TODO



Schema
------
TODO



Tag
---
TODO



Example
=======
TODO: A minimalist but complete example of a working PHP Rest API call.




To-do
=====
Code
----
*	Options to enable/disable comment types.
*	Option to specify comment command prefix. "rest" or "@rest\".
*	Ordering options for tags and/or paths and/or operations; sort according to list for tags
*	Parse and reference functions
*	Rethink pre-function comment(s); add to function/method or class?
*	Type alias/extension system
*	Command aliassing system.
*	Command line interface. Netbeans integration.

Swagger
-------
*	Full Type support in Swagger\Header object
*	Use (optional) Namespaces in @see and @uses
*	Security object+context + definitions
*	Set type (array of enumerated strings; can force unique?)
*	License: full/formatted names
*	Definitions: param/response
*	Date(-time) format helpers; if no timezone, add 'Z'. Use PHP Date parser.
*	Support object "additionalProperties" and "allOf"
*	Shortcut "get", "put", etc. operation methods as proper commands.

Quality
-------
*	Parsers; pass state object instead of keeping state in parser objects properties.
*	PHP: Cache previously parsed files; do not re-parse?
*	Unittests and Travis-CI integration.
*	PSR-* compliance

Validations
-----------
*	'body' and 'formData' Parameters cannot exist in single Operation.
*	'path' Parameters must reference part of Path.

Documentation
-------------
*	Explain basic command context. How does PHPDoc/JavaDoc explain this?
*	PHPDoc reference documentation.