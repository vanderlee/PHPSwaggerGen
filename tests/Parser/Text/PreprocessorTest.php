<?php

class Parser_Text_PreprocessorTest extends PHPUnit\Framework\TestCase
{

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::__construct
	 */
	public function testConstructor_Empty()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifdef_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$out = $object->preprocess('
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifdef_Defined()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('

			whatever

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifndef_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$out = $object->preprocess('
			ifndef test
			whatever
			endif
		');

		$this->assertEquals('

			whatever

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Ifndef_Defined()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('
			ifndef test
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Define()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$out = $object->preprocess('
			define test
			ifndef test
			whatever
			endif
		');

		$this->assertEquals('




		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Undef()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$out = $object->preprocess('
			define test
			undef test
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('





		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_If_NotDefined()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$out = $object->preprocess('
			if test red
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_If_AnyValue()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'green');
		$out = $object->preprocess('
			if test
			whatever
			endif
		');

		$this->assertEquals('

			whatever

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_If_NoValue()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test');
		$out = $object->preprocess('
			if test red
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_If_Mismatch()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'green');
		$out = $object->preprocess('
			if test red
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_If_Match()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'red');
		$out = $object->preprocess('
			if test red
			whatever
			endif
		');

		$this->assertEquals('

			whatever

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Else_Match()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('
			if test red
			whatever
			else
			otherwise
			endif
		');

		$this->assertEquals('



			otherwise

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Elif_Match()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('
			if test red
			whatever
			elif test green
			second
			elif test blue
			otherwise
			endif
		');

		$this->assertEquals('





			otherwise

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Elif_NoValue()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test', 'blue');
		$out = $object->preprocess('
			if test red
			whatever
			elif test green
			second
			elif test
			otherwise
			endif
		');

		$this->assertEquals('





			otherwise

		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::undefine
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_Undefine()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test');
		$object->undefine('test');
		$out = $object->preprocess('
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::define
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::resetDefines
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_ResetDefines()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->define('test');
		$object->resetDefines();
		$out = $object->preprocess('
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('



		', $out);
	}

	/**
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::addDefines
	 * @covers \SwaggerGen\Parser\Text\Preprocessor::preprocess
	 */
	public function testPreprocess_AddDefines()
	{
		$object = new \SwaggerGen\Parser\Text\Preprocessor();
		$this->assertInstanceOf('\SwaggerGen\Parser\Text\Preprocessor', $object);
		$object->addDefines(array('test' => true));
		$out = $object->preprocess('
			ifdef test
			whatever
			endif
		');

		$this->assertEquals('

			whatever

		', $out);
	}

}
