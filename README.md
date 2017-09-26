# SwaggerGen
Version 2.3.13

[![License](https://img.shields.io/github/license/vanderlee/PHPSwaggerGen.svg)]()
[![Build Status](https://travis-ci.org/vanderlee/PHPSwaggerGen.svg?branch=master)](https://travis-ci.org/vanderlee/PHPSwaggerGen)
[![Quality](https://scrutinizer-ci.com/g/vanderlee/PHPSwaggerGen/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vanderlee/PHPSwaggerGen)

Copyright &copy; 2014-2017 Martijn van der Lee [Toyls.com](http://toyls.com).

MIT Open Source license applies.

## Introduction
SwaggerGen is a PHP library for generating [Swagger](http://swagger.io/) REST
API documentation from PHP source code.

It reads comments starting with `@rest\`, containing commands describing the
API as you go.
Working with SwaggerGen is intended to be a natural extension to normal
PHP-documentor style documentation.
You can describe a REST API call similar to how you would describe method.

Using just a few simple commands like `@rest\endpoint /users` and
`@rest\method GET Get a list of all users` gets you a definition of an API.
By adding a `@rest\response 200 array(object(name:string, age:int[0,>, gender:enum(male,female)))`
statement, you've just defined exactly what it'll return.
You could have also just defined a `User` and do the same with a
`@rest\response 200 array(User)` statement.

SwaggerGen makes it quick and intuitive to write high quality documentation.

Use [Swagger-UI](https://github.com/swagger-api/swagger-ui) to read and test
your API, as in this example generated real-time with SwaggerGen:
[Example](example/docs/) (only available when running on a PHP server).

SwaggerGen is compatible with the latest
[Swagger 2.0 specification](http://swagger.io/specification/),
which forms the basis of the [Open API Initiative](https://openapis.org/).

## Installation
Requires PHP 5.4 or greater. PHP 5.3 is supported as long as no more recent
features are absolutely necessary. There is no guarantee SwaggerGen will
continue to work on PHP 5.3 in the future.

To install using Composer:

	composer require vanderlee/swaggergen

Make sure you use version 2.x.x or up.

SwaggerGen aims to be PSR-4 compatible, so you should be able to use it in any
package manager.

## Using SwaggerGen
The easiest part of generating Swagger documentation with SwaggerGen is setting
it up.

1.	Set up your (PSR-0, PSR-4 or custom) autoloader to use the SwaggerGen
	directory.

	You can take a look at the autoloader in the example folder if you don't
	already have an autoloader.

2.	Create an instance of the `/SwaggerGen/SwaggerGen` class.

	You can (and are advised to) specify the domainname of your server and the
	path to the API in the constructor.

3.	Call the `array SwaggerGen->getSwagger(string[] $filenames)` method to
	generate the documentation.

	Just provide the files which contain the operation definitions of your API.
	If your API uses other files, just specify an array of directories in the
	`SwaggerGen` constructor and these files will be automatically parsed when
	needed.

4.	You're done. Your documentation is generated. All that's left to do is
	output it. Store it in a file or return it real-time.

If you want to use the preprocessor, you'll probably want to call the
`SwaggerGen->define(string $name, string $value)` method of your `SwaggerGen` instance after
step 2 to define preprocessor variable names.

The following is a typical example:

```php
// Assuming you don't already have an autoloader
spl_autoload_register(function ($classname) {
	include_once __DIR__ . $classname . '.php';
});

$SwaggerGen = new \SwaggerGen\SwaggerGen(
	$_SERVER['HTTP_HOST'],
	dirname($_SERVER['REQUEST_URI']),
	[__DIR__ . '/api']
);
$SwaggerGen->define('admin');				// admin = 1
$SwaggerGen->define('date', date('Y-m-d'));	// date = "2015-12-31"
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	$SwaggerGen->define('windows');	// windows = 1 (only if on Windows OS)
}
$swagger = $SwaggerGen->getSwagger(['Example.php']);

header('Content-type: application/json');
echo json_encode($swagger);
```

# SwaggerGen class
The only class you need to know about is the `SwaggerGen` class in the similarly
names `SwaggerGen` namespace.

## `__construct($host = '', $basePath = '', $dirs = array())`
Create a new SwaggerGen object with the given `host` and `basePath` and provide
a set of `dirs` to use for scanning for classes that may be referenced
from the sourcecode files you're about to scan.
*	`$host` should be the domain name, i.e. `www.example.com`.
*	`$basePath` should be the URL path to the root of the API, i.e. `/api/v1`.

## `mixed getSwagger($files, $dirs = array(), $format = self::FORMAT_ARRAY)`
Generate Swagger/OpenAPI documentation by scanning the provided list of `files`.
Optionally you can specify additional `dirs` to scan for class files and
provide a `format` to specify how you want to output the documentation.

By default, the documentation is output as an array, ready for encoding as JSON,
YAML or for manual post-processing. The following formats are available as
constants of the `SwaggerGen` class.
*	`FORMAT_ARRAY` output the raw array.
*	`FORMAT_JSON` JSON-encoded output (mimetype `application/json`).
*	`FORMAT_JSON_PRETTY` JSON-encoded output with a human-friendly layout
	(mimetype `application/json`).
*	`FORMAT_YAML` YAML (UTF-8 character encoding) output
	(mimetype `application/x-yaml` (most common) or `text/yaml`).

## `define($name, $value = 1)`
Define a value to be used by the preprocessor commands.
By default, it's value will be set to `1`.

## `undefine($name)`
Undefine a value, so it is no longer recognized by the preprocessor commands.

# Creating documentation
SwaggerGen takes a number of of source files and scans the comments for
commands it understands. The following is a short example of the type of
comments SwaggerGen understands:

```php
/*
 * @rest\description SwaggerGen 2 Example API
 * @rest\title Example API
 * @rest\contact http://example.com Arthur D. Author
 * @rest\license MIT
 * @rest\security api_key apikey X-Api-Authentication header Authenticate using this fancy header
 * @rest\require api_key
 */
```

## Comments
All comments are parsed, this includes both doc-comments (`/** ... */`) and
normal comments, both single line (`// ...`) and multi-line (`/* ... */`).

Comments that are attached to functions, methods and classes. Any doc-comment
immediately preceeding a function, method or class will be attached to that
function, method or class. Other comments will be attached to the function,
method or class containing them. For instance, SwaggerGen comments within a
function will be attached to that function.

## Commands

All commands must be prefixed with `@rest\` to distinguish between SwaggerGen
statements and normal comment statements and statements from other tools such
as PHP-Documentor.

All commands are multi-line by default; any line(s) after the command that do
not start with an at-sign (`@`) is automatically appended to the command on the
previous line.

You can reference SwaggerGen documentation for other functions, methods or
classes by using the `uses` command. This command lets you specify an other
function, method or class whose documentation to include.

Commands are processed in the order in which they appear. This includes any
documentation referenced with the `uses` command.

## Contexts
SwaggerGen uses a stack of contexts. Each context represents a certain part of
the Swagger documentation that will be generated. Each context supports a few
commands which hold meaning within that context.

You initially start at the Swagger context.

You can switch contexts using some of the commands available within the current
context. In this manual, whenever a command switches the context, it is
marked using '&rArr; Context name' at the end of the command syntax description.

If a command is not recognized in the current context, the context is removed
from the top of the stack and the previous context tries to handle the command.
If no context is able to handle the command, SwaggerGen will report this as an
error.

# Preprocessor commands
SwaggerGen has a limited set of preprocessor statements to remove or change
parts of the generated documentation at run-time.

The preprocessor statements are loosely based on the C/C++ preprocessors.

The work by defining values for variable names and checking whether or not a
variable name is defined or checking if a variables name has a specific value.

SwaggerGen currently has no predefined variables, but you can define variables
yourself by assigning them to the SwaggerGen parser before scanning starts.

Preprocessor statments may be nested and are available for PHP and text.

### `define` *`name [value]`*
Define a variable name and optionally assign a value to it.

### `undef` *`name`*
Remove the definition a variable name.

### `if` *`name [value]`*
If the variable name is defined *and*, if provided, it's value is equal to
the specified value, then process all following SwaggerGen commands upto
the next preprocessor command.
Otherwise, do not process those commands.

### `ifdef` *`name`*
If the variable name is defined, then process all following SwaggerGen
commands upto the next preprocessor	command.
Otherwise, do not process those commands.

### `ifndef` *`name`*
If the variable name is *not* defined, then process all following SwaggerGen
commands upto the next preprocessor	command.
Otherwise, do not process those commands.

### `else`
If the previous `if...` or `elif` preprocessor command did *not* match,
then process all following SwaggerGen commands upto the next preprocessor
command.
Otherwise, do not process those commands.

### `elif` *`name [value]`*
If the previous `if...` or `elif` preprocessor command did *not* match
*and* if the variable name is defined *and*, if provided, it's value is
equal to the specified value, then process all following SwaggerGen
commands upto the next preprocessor command.
Otherwise, do not process those commands.

### `endif`
End the previous `if...`, `elif` or `else` preprocessor command's block of
SwaggerGen commands.

# SwaggerGen context and commands
Ordered alphabetically for reference

The following commands can be used from within any context.

### `uses` *`reference`*
Include a reference to another function, method or class.

For example:
*	`uses functionName`
*	`uses self::staticMethodName`
*	`uses $this->methodName`
*	`uses ClassName::staticMethodName`
*	`uses ClassName->methodName`

SwaggerGen makes no distinction between the `self` and `this` or between
the static and dynamic `::` and `->`. These can be interchanged without
any impact. Though it is advised to stick to the proper terms.

Class inheritance is used if a method cannot be found within the indicated
class.

alias: `see`

## BodyParameter
Represents a body parameter.

For a list of commands, read the chapter on  **Parameter definitions**.
The available command depend on the particular type.

## Contact
Contains the contact information for the API.

### `email` *`email`*
Set the email address of the contact person.

### `name` *`text ...`*
Set the name of the contact person.

### `url` *`email`*
Set the URL where users can contact the maintainer(s).

## Error
Represents a response with an error statuscode.

See the Response context for commands.

## ExternalDocumentation
Contains an URL reference to additional documentation of the context which
created this context.

### `description` *`text ...`*
Set the description text for this external documentation.

### `url` *`url`*
Set the URL to the external documentation.

## Header
Represents a response header.

### `description` *`text ...`*
Set the description text of this response header.

## Info
Contains non-technical information about the API, such as a description,
contact details and legal small-print.

### `contact` *`[url] [email] [name ...]`* &rArr; Contact
Set the contactpoint or -person for this API.
You can specify the URL, email address and name in any order you want.
The URL and email address will be automatically detected, the name will
consist	of all text remaining (properly separated with whitespace).

### `description` *`text ...`*
	Set the description for the API.

### `license` *`[url] [name ...]`* &rArr; License
Set the license for this API.
You can specify the URL in name in any order you want.
If you omit the URL, you can use any number of predefined names, which are
automatically expanded to a full URL, such as `gpl`, `gpl-2.1` or `bsd`.

### `terms` *`text ...`*
Set the text for the terms of service of this API.

alias: `tos`, `termsofservice`

### `title` *`text ...`*
Set the API title.

### `version` *`number`*
Set the API version number.

## License
Represents the name and URL of the license that applies to the API.

### `name` *`text ...`*
Set the name of the license.
If you haven't set a URL yet, a URL may be automatically set if it is one
of a number of recognized license names, such as `mpl` or `apache-2`

### `url` *`text ...`*
Set the URL of the license.

## Operation
Describes an operation; a call to a specifc path using a specific method.

### `body`/`body?` *`definition name [description ...]`* &rArr; BodyParameter
Add a new form Parameter to this operation.

Use `body` to make the parameter required.
Use `body?` (with a question mark) to make the parameter optional.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `consumes` *`mime1 [mime2 ... mimeN]`*
Adds mime types that this operation is able to understand.
E.g. "application/json",  "multipart/form-data" or
"application/x-www-form-urlencoded".

### `deprecated`
Mark this operation as deprecated.

### `description` *`text ...`*
Set the long description of the operation.

### `doc` *`url [description ...]`* &rArr; ExternalDocumentation
Set an URL pointing to more documentation.

alias: `docs`

### `error` *`statuscode [description]`* &rArr; Error
Add a possible error statuscode that may be returned by this
operation, including an optional description text.

If no description is given, the standard reason for the statuscode will
be used instead.

### `errors` *`statuscode1 [statuscode2 ... statuscodeN]`*
Add several possible error statuscodes that may be returned by this
operation.

### `form`/`form?` *`definition name [description ...]`* &rArr; Parameter
Add a new form Parameter to this operation.

Use `form` to make the parameter required.
Use `form?` (with a question mark) to make the parameter optional.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `header`/`header?` *`definition name [description ...]`* &rArr; Parameter
Add a new header Parameter to this operation.

Use `header` to make the parameter required.
Use `header?` (with a question mark) to make the parameter optional.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `id` *`name`*
Set an operation id for this operation.

`name`  The ID name must be uniue among all operations in the document.
If you specify an ID that has already been set, an exception will be thrown.

### `parameter` *`name`*
Add a new parameter by referencing the name of a globally defined parameter.

`name`  The globally unique name of the parameter reference.

alias: `param`

### `path` *`definition name [description ...]`* &rArr; Parameter
Add a new path Parameter to this operation.

`path` parameters are always required; they cannot be optional.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `produces` *`mime1 [mime2 ... mimeN]`*
Adds mime types that this operation is able to produce.
E.g. "application/xml" or "application/json".

### `query`/`query?` *`definition name [description ...]`* &rArr; Parameter
Add a new query Parameter to this operation.

Use `query` to make the parameter required.
Use `query?` (with a question mark) to make the parameter optional.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `require` *`security1 [security2 ... securityN]`*
Set the required security scheme(s) for this operation.

Security schemes can be defined in the **Swagger** context.

### `response` *`statuscode definition description`* &rArr; Response
Adds a possible response status code with a definition of the data that
will be returned. Though for error statuscodes you would typically use
the `error` or `errors` commands, you can use this command for those
status codes as well, including a return definition.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `response` *`reference statuscode`*
Reference a response definition.

The `reference` name must exist as a Response definition defined in the
**Swagger** context.

Note that this is one of two possible signatures for the `response` command.

### `schemes` *`scheme1 [scheme2 ... schemeN]`*
Add any number of schemes to the operation.

### `summary` *`text ...`*
Set the a short summary description of the operation.

### `tags` *`tag1 [tag2 ... tagN]`*
Add any number of tags to the operation.

## Parameter
Represents either a form, query, header of path parameter.

For a list of commands, read the chapter on  **Parameter definitions**.
The available command depend on the particular type.

## Path
Represents a URL endpoint or Path.

### `operation` *`method [summary ...]`* &rArr; Operation
Add a new operation to the most recently specified endpoint.
Method can be any one of `get`, `put`, `post`, `delete` or `patch`.

### `description` *`text ...`*
If a tag exists, sets the description for the tag, otherwise to nothing.

## Response
Represents a response.

### `header` *`type name [description]`* &rArr; Header
Add a header to the response.

`type` must be either `string`, `number`, `integer`, `boolean` or `array`.

`name` must be a valid HTTP header name. I.e. `X-Rate-Limit-Limit`.

## Schema
Represents a definitions of a type, such as an array.

### `doc` *`url [description ...]`* &rArr; ExternalDocumentation
Set an URL pointing to more documentation.

alias: `docs`

### `title` *`text ...`*
Set the title of this schema.

### `description` *`description ...`*
Set the description of this schema.

For a list of other commands, read the chapter on  **Parameter definitions**.
The available command depend on the particular type.

## SecurityScheme
Represents a single way of authenticating the user/client to the server.
You specify the type of security scheme and it's settings using the `security`
command from the Swagger context.

### `description` *`text ...`*
Set the description.

### `scope` *`name [description ...]`*
Add a new oAuth2 scope name with optional description.

## Swagger
Represents the entire API documentation.
This is the initial context for commands.

### `consumes` *`mime1 [mime2] ... [mimeN]`*
Adds mime types that the API is able to understand. E.g.
"application/json",  "multipart/form-data" or
"application/x-www-form-urlencoded".

alias: `consume`

### `contact` *`[url] [email] [name ...]`* &rArr; Contact
Set the contactpoint or -person for this API.
You can specify the URL, email address and name in any order you want.
The URL and email address will be automatically detected, the name will consist
of all text remaining (properly separated with whitespace).

### `definintion` *`name`* &rArr; Schema
Start definition of a Schema using the reference name specified. 

Definitions can be specified as read only using exclamation point at the end of
the definition command. E.g. `definition! user` will create a user model that
will appear in GET responses and be omitted from POST, PUT, and PATCH requests.

alias: `model` (for historical reasons)

### `description` *`text ...`* &rArr; Info
Set the description for the API.

### `doc` *`url [description ...]`* &rArr; ExternalDocumentation
Set an URL pointing to more documentation.

alias: `docs`

### `endpoint` *`/path [tag] [description ...]`* &rArr; Path
Create an endpoint using the /path.
If tag is set, the endpoint will be assigned to the tag group of that name.
If a description is set, the description of the group will be set.

### `license` *`[url] [name ...]`* &rArr; License
Set the license for this API.
You can specify the URL in name in any order you want.
If you omit the URL, you can use any number of predefined names, which are
automatically expanded to a full URL, such as `gpl`, `gpl-2.1`, `mit` or `bsd`.

### `produces` *`mime1 [mime2] ... [mimeN]`*
Adds mime types that the API is able to produce. E.g. "application/xml" or
"application/json".

alias: `produce`

### `require` *`name [scopes]`*
Set the required security scheme names.
If multiple names are given, they must all apply.
If an `oath2` scheme is specified, you may

### `response` *`name definition description`* &rArr; Response
Adds a response definition with a schema definition of the data that will be
returned. You can omit the `definition` by specifying `null` instead.

See the chapter on  **Parameter definitions** for a detailed
description of all the possible definition formats.

### `schemes` *`scheme1 [scheme2] ... [schemeN]`*
Adds protocol schemes. E.g. "http" or "https".

alias: `scheme`

### `security` *`name type [params ...]`* &rArr; SecurityScheme
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
or `accesscode`. For type `accesscode` you must specify two URL's, for
authorization and token respectively, for the other types only one URL is
needed. Optionally follow with a description text. You may need to add scopes
using the `scope` command afterwards.

*	`security` *`name`* `basic` *`[description ...]`*
*	`security` *`name`* `apikey` *`header-name`* `header` *`[description ...]`*
*	`security` *`name`* `apikey` *`query-variable`* `query` *`[description ...]`*
*	`security` *`name`* `oauth2 implicit` *`auth-url [description ...]`*
*	`security` *`name`* `oauth2 password` *`token-url [description ...]`*
*	`security` *`name`* `oauth2 application` *`token-url [description ...]`*
*	`security` *`name`* `oauth2 accesscode` *`auth-url token-url [description ...]`*

### `tag` *`tag [description ...]`* &rArr; Tag
Specifies a tag definition; essentially the category in which an endpoint path
will be grouped together.

alias: `api` (for historical reasons).

### `terms` *`text ...`* &rArr; Info
Set the text for the terms of service of this API.

alias: `tos`, `termsofservice`

### `title` *`text ...`* &rArr; Info
Set the API title.

### `version` *`number`* &rArr; Info
Set the API version number.

## Tag
A tag is used to group paths and operations together in logical categories.

### `description` *`text ...`*
Set the description.

### `doc` *`url [description ...]`* &rArr; ExternalDocumentation
Set an URL pointing to more documentation.

alias: `docs`

# Parameter definitions

## string, byte, binary, password
Represents a text.

	type(pattern)[0,>=default

*	type: `string` or `binary`,
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` value means zero.
	Empty `max` value means infinity.
*	default: any valid text not containing whitespace.

### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.

### Examples
*	**`string`** A simple text field.
*	**`string(^[a-z]{2}-[A-Z]{2}$)`** String matching ISO "ll-CC" locale.
*	**`string[,256>=red`** A text of at most 255 characters, default to "red".
*	**`binary[1,8]`** Upto 8 binary digits, requiring atleast one.


## int32 (integer, int), int64 (long)
Represents numbers without decimals.

	type[0,>=default

*	type: `integer`, `int`, `int32`, `long` or `int64`.
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` or `max` values means infinity.
*	default: any valid integer.

### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.
*	**`step` *value*** Set the stepsize between numbers.

### Examples
*	**`int`** 32-bit integer without a default or limited range.
*	**`long<,0>`** 64-bit negative integers only.
*	**`integer[0,>=100`** 32-bit positive integer or zero, default to 100.


## float, double
Represents floating point numbers (with decimals).

	type[0,>=default

*	type: `float` or `double`
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` or `max` values means infinity.
*	default: any valid integer.

### Commands
*	**`default` *value*** Set the default value.
*	**`enum` *value1 value2 ... valueN*** Set or add allowed values.
*	**`step` *value*** Set the stepsize between numbers.

### Examples
*	**`float`** 32-bit floating point number without a default or limited range.
*	**`double<,1>`** 64-bit floating point numbers upto (but not including) 1.
*	**`float<0,>=0.1`** 32-bit positive numbers, excluding 0, default to 0.1.


## boolean (bool)
A true/false choice.

	type=default

*	type: `boolean` or `bool`.
*	default: `true`, `false`, 1 (true) or 0 (false).

### Commands
*	**`default` *value*** Set the default value.

### Examples
*	**`boolean`** A basic boolean.
*	**`bool=true`** A boolean, default to true.


## date, date-time (datetime)
Special type of string which is limited to dates only

	type=default

*	type: `date`, `date-time` or `datetime`,
*	default: Any valid date format recognized by the [PHP DateTime object](http://php.net/manual/en/datetime.formats.php).

### Commands
*	**`default` *date*** Set the default value.

### Examples
*	**`date`** A simple date
*	**`datetime=2015-12-31T12:34:56Z`** Date and time set to a default without
	a timezone offset.
*	**`datetime=2015-12-31T12:34:56.001+01:00`** Date and time set to a default
	value with fractional seconds and a timezone offset.

## csv (array), ssv, tsv, pipes, multi
List of items

	type(definition)[0,>

*	type: `csv`, `array`, `ssv`, `tsv`, `pipes`, or `multi`.
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` value means zero.
	Empty `max` value means infinity.
*	default: any valid text not containing whitespace.
*	definition: a definition of the type of the items in the list. It is possible to
	define lists as items, creating multidimensional arrays.

### Commands
*	**`min` *value*** Set the minimum number of items required.
*	**`max` *value*** Set the maximum number of items allowed.
*	**`items` *definition*** Set the definition of the items in this list.

### Types
*	**`csv`** Comma (`,`) separated. I.e. `red,green,blue`. Alias: `array`.
*	**`ssv`** Space ( ) separated. I.e. `red green blue`.
*	**`tsv`** Tab-separated. I.e. `red	green	blue`.
*	**`pipes`** Pipe (`|`) separated. I.e. `red|green|blue`.
*	**`multi`** query-string formatted. I.e. `color=red&color=green&color=blue`.
	This choice is only available for `form` and `query` parameters.

### Examples
*	**`csv(string)`** A comma-separated list of strings.

## file
A file.

	file

No further definition is possible. There are no command.

### Examples
*	**`file`** A file.

## object
Object with properties. Typically used as key-value map

	object(definition)[0,>

*	type: `object`.
*	range: [min,max].
	Use `[` or `]` for inclusive and `<` or `>` for	exclusive.
	Empty `min` value means zero properties (no minimum).
	Empty `max` value means infinite properties (no maximum).
*	definition: a comma-separated list of property definitions in the form of
	`key:definition`, where `key` can be any sequence of characters except `:` or
	`?` or `!`. The `?` means that key is optional. The `!` means the key is read only. 
	Read only implies optional as well.

### Commands
*	**`min` *value*** Set the minimum number of items required.
*	**`max` *value*** Set the maximum number of items allowed.
*	**`property` *definition name*** Add a required property.
*	**`property?` *definition name*** Add an optional property.
*   **`property!` *definition name*** Add a read only property.

### Examples
*	**`object(age:int[18,25>)`** An object containing a single key `age` with
	an integer value greater or equal to 18 and less than 25.
*	**`object(age:int,name?:string[2,>)`** An object containing an `age` and an
	optional `name` string, where the value must be atleast two characters
	long.
*	**`object()[4,8]`** An object containing four to eight unknown properties.

## enum
Special type of string which is limited to one of a number of predefined values.

	enum(value1,value1,...,valueN)=default

*	values: any text not containing whitespace or commas.
*	default: any of the specified texts.

### Commands
See string.

### Examples
*	**`enum(red,green,blue)=red`** A string containing either "red", "green" or
	"blue", default to "red".

## uuid
Special type of string which accepts
[RFC 4122](https://www.ietf.org/rfc/rfc4122.txt) compliant Universally Unique
IDentifier (UUID) strings. The default value is validated to ensure only valid
UUID's are specified.

	uuid=default

*	default: any of the specified texts.

### Commands
See string.

### Examples
*	**`uuid=123e4567-e89b-12d3-a456-426655440000`** A uuid string, default to
	the uuid "123e4567-e89b-12d3-a456-426655440000".

## refobject
Reference to a globally defined `definition` (a.k.a. `model`) object.

	refobject(definitionName)

or

	definitionName

*	definitionName: the name of the globally defined `definition`.

### Examples
*	**`refobject(Address)`** Reference the a globally defined model named
	`Address`.
*	**`Address`** Reference the a globally defined model named
	`Address`.

### Notes
Usually, using the definition name alone is good enough.
Use `refobject(...)` if you are using a name which is also used as a builtin
parameter type, such as `string` or `object`.
It is best practice to start all definition names with an upper case character
(i.e. `Address`).
Using `refobject(...)` also offers the safest forward-compatible strategy if
you do not start definition names with upper case (i.e. `address`).

# Appendices
## Mime types
Some commands, such as `consumes` and `produces` take mime types as arguments.
Instead of specifying the full mime types, you can any of the following
predefined shorthands (case insensitive):

	fileform	multipart/form-data
	form		application/x-www-form-urlencoded
	json		application/json
	text		text/plain
	utf8		text/plain; charset=utf-8
	yml			application/x-yaml
	yaml		application/x-yaml
	php			text/x-php
	xml			text/xml

## Licenses
A selection of shorthands are available for licenses.
If you want another license added to it, please submit an issue or create a
pull request. The file you want to edit is `/SwaggerGen/Swagger/License.php`.

These are the license shorthands currently available:

	artistic-1.0	http://opensource.org/licenses/artistic-license-1.0
	artistic-1		http://opensource.org/licenses/artistic-license-1.0
	artistic-2.0	http://opensource.org/licenses/artistic-license-2.0
	artistic-2		http://opensource.org/licenses/artistic-license-2.0
	artistic		http://opensource.org/licenses/artistic-license-2.0
	bsd-new			https://opensource.org/licenses/BSD-3-Clause
	bsd-3			https://opensource.org/licenses/BSD-3-Clause
	bsd-2			https://opensource.org/licenses/BSD-2-Clause
	bsd				https://opensource.org/licenses/BSD-2-Clause
	epl-1.0			http://www.eclipse.org/legal/epl-v10.html
	epl-1			http://www.eclipse.org/legal/epl-v10.html
	epl				http://www.eclipse.org/legal/epl-v10.html
	apache-2.0		http://www.apache.org/licenses/LICENSE-2.0.html
	apache-2		http://www.apache.org/licenses/LICENSE-2.0.html
	apache			http://www.apache.org/licenses/LICENSE-2.0.html
	gpl-1.0			https://www.gnu.org/licenses/gpl-1.0.html
	gpl-1			https://www.gnu.org/licenses/gpl-1.0.html
	gpl-2.0			https://www.gnu.org/licenses/gpl-2.0.html
	gpl-2			https://www.gnu.org/licenses/gpl-2.0.html
	gpl-3.0			http://www.gnu.org/licenses/gpl-3.0.html
	gpl-3			http://www.gnu.org/licenses/gpl-3.0.html
	gpl				http://www.gnu.org/licenses/gpl-3.0.html
	lgpl-2.0		http://www.gnu.org/licenses/lgpl-2.0.html
	lgpl-2.1		http://www.gnu.org/licenses/lgpl-2.1.html
	lgpl-2			http://www.gnu.org/licenses/lgpl-2.1.html
	lgpl-3.0		http://www.gnu.org/licenses/lgpl-3.0.html
	lgpl-3			http://www.gnu.org/licenses/lgpl-3.0.html
	lgpl			http://www.gnu.org/licenses/lgpl-3.0.html
	mit				http://opensource.org/licenses/MIT
	mpl-1.1			https://www.mozilla.org/en-US/MPL/1.1/
	mpl-1			https://www.mozilla.org/en-US/MPL/1.1/
	mpl-2.0			https://www.mozilla.org/en-US/MPL/
	mpl-2			https://www.mozilla.org/en-US/MPL/
	mpl				https://www.mozilla.org/en-US/MPL/
	mspl			https://msdn.microsoft.com/en-us/library/ff648068.aspx


# Example
To view an example of Swagger documentation generated with SwaggerGen, visit
the [Example API documentation](./example/docs/).

The following is a fragment of code from this example:

```php
/**
 * @rest\endpoint /user/{username}
 * @rest\method GET Get a list of all users
 * @rest\path String username Name of the user
 * @rest\see self::request
 */
private function getUser($name)
{
	/*
	 * @rest\model User
	 * @rest\property int age Age of the user in years
	 * @rest\property int height Height of the user in centimeters
	 */
	return $this->data['users'][$name]; // @rest\response OK object(age:int[0,100>,height:float) User
}
```
