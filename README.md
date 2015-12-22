SwaggerGen
==========
Version v2.0-beta-1

[![Build Status](https://travis-ci.org/vanderlee/PHPSwaggerGen.svg?branch=master)](https://travis-ci.org/vanderlee/PHPSwaggerGen)

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



Preprocessor commands
=====================
TODO: Document these

*	### `define` *`name [value]`*
*	### `undef` *`name`*
*	### `if` *`name [value]`*
*	### `ifdef` *`name`*
*	### `ifndef` *`name`*
*	### `else`
*	### `elif` *`name [value]`*
*	### `endif`



Commands by context
===================

Swagger
-------
Represents the entire API documentation.
This is the initial context for commands.

*	### `title` *`text ...`* --> Info
	Set the API title.

*	### `description` *`text ...`* --> Info
	Set the description for the API.

*	### `version` *`number`* --> Info
	Set the API version number.

*	### `terms` *`text ...`* --> Info
	Set the text for the terms of service of this API.

	alias: `tos`, `termsofservice`

*	### `contact` *`[url] [email] [name ...]`* --> Contact
	Set the contactpoint or -person for this API.
	You can specify the URL, email address and name in any order you want.
	The URL and email address will be automatically detected, the name will consist
	of all text remaining (properly separated with whitespace).

*	### `license` *`[url] [name ...]`* --> License
	Set the license for this API.
	You can specify the URL in name in any order you want.
	If you omit the URL, you can use any number of predefined names, which are
	automatically expanded to a full URL, such as `gpl`, `gpl-2.1`, `mit` or `bsd`.

*	### `schemes` *`scheme1 [scheme2] ... [schemeN]`*
	Adds protocol schemes. E.g. "http" or "https".

	alias: `scheme`

*	### `consumes` *`mime1 [mime2] ... [mimeN]`*
	Adds mime types that the API is able to understand. E.g.
	"application/json",  "multipart/form-data" or
	"application/x-www-form-urlencoded".

	alias: `consume`

*	### `produces` *`mime1 [mime2] ... [mimeN]`*
	Adds mime types that the API is able to produce. E.g. "application/xml" or
	"application/json".

	alias: `produce`

*	### `define` *`type name`* --> Schema
	Start definition of a Schema (type is either `params` or `parameters`), using
	the reference name specified.

	alias: `definition`, `model` (don't specify type; always `params`)

*	### `tag` *`tag [description ...]`* --> Tag
	Specifies a tag definition; essentially the category in which an endpoint path
	will be grouped together.

	alias: `api`

*	### `endpoint` *`/path [tag] [description ...]`* --> Path
	Create an endpoint using the /path.
	If tag is set, the endpoint will be assigned to the tag group of that name.
	If a description is set, the description of the group will be set.

*	### `security` *`name type [params ...]`* --> SecurityScheme
	Define a security method, available to the API and individual operations.
	Name can be any random name you choose. These names will be used to reference
	to the security shemes later on.

	`Type` must be either `basic`, `apikey` or `oauth2`.
	The parameters depend on the type.

	For `basic`, you can only specify a description text.

	For `apikey`, you must first specify a name to use for the query parameter or
	header, then use either `query` or `header` to set the type of apikey.
	Optionally followed by a description text.

	For `oauth2`, you must set the flow type `implicit`, `password`, `application`
	or `accesscode`. For type `password` you must specify two URL's, for
	authorization and token respectively, for the other types only one URL is
	needed. Optionally follow with a description text. You may need to add scopes
	using the `scope` command afterwards.

	*	`security` *`name`* `basic` *`[description ...]`*
	*	`security` *`name`* `apikey` *`header-name`* `header` *`[description ...]`*
	*	`security` *`name`* `apikey` *`query-variable`* `query` *`[description ...]`*
	*	`security` *`name`* `oauth2 implicit` *`auth-url [description ...]`*
	*	`security` *`name`* `oauth2 password` *`auth-url token-url [description ...]`*
	*	`security` *`name`* `oauth2 application` *`token-url [description ...]`*
	*	`security` *`name`* `oauth2 accesscode` *`token-url [description ...]`*

*	### `require` *`name [scopes]`*
	Set the required security scheme names.
	If multiple names are given, they must all apply.
	If an `oath2` scheme is specified, you may

Info
----
Contains non-technical information about the API, such as a description,
contact details and legal small-print.

*	### `title` *`text ...`*
	Set the API title.

*	### `description` *`text ...`*
	Set the description for the API.

*	### `version` *`number`*
	Set the API version number.

*	### `terms` *`text ...`*
	Set the text for the terms of service of this API.

	alias: `tos`, `termsofservice`

*	### `contact` *`[url] [email] [name ...]`* --> Contact
	Set the contactpoint or -person for this API.
	You can specify the URL, email address and name in any order you want.
	The URL and email address will be automatically detected, the name will
	consist	of all text remaining (properly separated with whitespace).

*	### `license` *`[url] [name ...]`* --> License
	Set the license for this API.
	You can specify the URL in name in any order you want.
	If you omit the URL, you can use any number of predefined names, which are
	automatically expanded to a full URL, such as `gpl`, `gpl-2.1` or `bsd`.

Contact
-------
@TODO

License
-------
Represents the name and URL of the license that applies to the API.

*	### `name` *`text ...`*
	Set the name of the license.
	If you haven't set a URL yet, a URL may be automatically set if it is one
	of a number of recognized license names, such as `mpl` or `apache-2`

*	### `url` *`text ...`*
	Set the URL of the license.

Schema
------
@TODO

Tag
---
A tag is used to group paths and operations together in logical categories.

*	### `description` *`text ...`*
	Set the description.

Path
----
Represents a URL endpoint or Path.

*	### `operation` *`method [summary ...]`* --> Operation
	Add a new operation to the most recently specified endpoint.
	Method can be any one of `get`, `put`, `post`, `delete` or `patch`.

*	### `description` *`text ...`*
	If a tag exists, sets the description for the tag, otherwise to nothing.


SecurityScheme
--------------
Represents a single way of authenticating the user/client to the server.
You specify the type of security scheme and it's settings using the `security`
command from the Swagger context.

*	### `description` *`text ...`*
	Set the description.

*	### `scope` *`name [description ...]`*
	Add a new oAuth2 scope name with optional description.





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
*	**`string(^[a-z]{2}-[A-Z]{2}$)`** String matching ISO "ll-CC" locale.
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
*	Use different mechanism for preprocessor: # or such prefix

Swagger
-------
*	Full Type support in Swagger\Header object
*	Use (optional) Namespaces in @see and @uses
*	Set type (array of enumerated strings; can force unique?)
*	License: full/formatted names
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