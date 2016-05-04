<?php

class Parser_Php_PreprocessorTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::__construct
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::getPrefix
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::setPrefix
	 */
	public function testConstructor_Prefix()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$this->assertEquals('rest', $object->getPrefix());

		$object = new \SwaggerGen\Parser\Php\Preprocessor('foo');
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$this->assertEquals('foo', $object->getPrefix());

		$object->setPrefix('bar');
		$this->assertEquals('bar', $object->getPrefix());
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifdef_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifdef_Defined()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * @rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifndef_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifndef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifndef test
			 * @rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifndef_Defined()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifndef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifndef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Define()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\define test
			 * @rest\ifndef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\define test
			 * !rest\ifndef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Undef()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\define test
			 * @rest\undef test
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\define test
			 * !rest\undef test
			 * !rest\ifdef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_If_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_If_AnyValue()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'green');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test
			 * @rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_If_NoValue()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_If_Mismatch()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'green');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_If_Match()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'red');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * @rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Else_Match()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\else
			 * @rest\otherwise
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\else
			 * @rest\otherwise
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Elif_Match()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\elif test green
			 * @rest\second
			 * @rest\elif test blue
			 * @rest\otherwise
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\elif test green
			 * !rest\second
			 * !rest\elif test blue
			 * @rest\otherwise
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Elif_NoValue()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('<?php
			/**
			 * @rest\if test red
			 * @rest\whatever
			 * @rest\elif test green
			 * @rest\second
			 * @rest\elif test
			 * @rest\otherwise
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\if test red
			 * !rest\whatever
			 * !rest\elif test green
			 * !rest\second
			 * !rest\elif test
			 * @rest\otherwise
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifdef_AffectPrefixedOnly()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @other\whatever
			 * @whatever
			 * whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * !rest\whatever
			 * @other\whatever
			 * @whatever
			 * whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::undefine
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_Undefine()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');
		$object->undefine('test');
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::resetDefines
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_ResetDefines()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');
		$object->resetDefines();
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * !rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::addDefines
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_AddDefines()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->addDefines(array('test' => true));
		$out = $object->preprocess('<?php
			/**
			 * @rest\ifdef test
			 * @rest\whatever
			 * @rest\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !rest\ifdef test
			 * @rest\whatever
			 * !rest\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocess
	 */
	public function testPreprocess_AlternativePrefix()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor('foo');
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$out = $object->preprocess('<?php
			/**
			 * @foo\ifdef test
			 * @foo\whatever
			 * @rest\whatever
			 * @foo\endif
			 */
		');

		$this->assertEquals('<?php
			/**
			 * !foo\ifdef test
			 * !foo\whatever
			 * @rest\whatever
			 * !foo\endif
			 */
		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Php\Preprocessor::preprocessFile
	 */
	public function testPreprocessFile()
	{
		$object = new \SwaggerGen\Parser\Php\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Php\Preprocessor', $object);
		$object->define('test');

		$out = $object->preprocessFile(__DIR__ . '/PreprocessorTest/testPreprocessFile.php');

		$this->assertEquals('<?php

/**
 * !rest\ifdef test
 * @rest\whatever
 * !rest\endif
 */', $out);
	}

	//@todo  preprocess($content) -> mingle with other PHP code
	//@todo  preprocess($content) -> Condition within comment
	//@todo  preprocess($content) -> Condition over comments
	//@todo  preprocess($content) -> Condition over code
}
