<?php

class FileTypeTest extends SwaggerGen_TestCase
{

	protected $parent;

	protected function setUp()
	{
		$this->parent = new \SwaggerGen\Swagger\Swagger;
	}

	protected function assertPreConditions()
	{
		$this->assertInstanceOf('\SwaggerGen\Swagger\AbstractObject', $this->parent);
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructNotAFile()
	{
		$this->expectException('\SwaggerGen\Exception', "Not a file: 'wrong'");

		$object = new SwaggerGen\Swagger\Type\FileType($this->parent, 'wrong');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructNotParameter()
	{
		$this->expectException('\SwaggerGen\Exception', "File type 'file' only allowed on form parameter");

		$object = new SwaggerGen\Swagger\Type\FileType($this->parent, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructNotFormParameter()
	{
		$this->expectException('\SwaggerGen\Exception', "File type 'file' only allowed on form parameter");

		$parameter = new SwaggerGen\Swagger\Parameter($this->parent, 'query', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructNoFormConsumes()
	{
		$this->expectException('\SwaggerGen\Exception', "File type 'file' without valid consume");

		$operation = new SwaggerGen\Swagger\Operation($this->parent);
		$operation->handleCommand('consumes', 'text');
		$parameter = new SwaggerGen\Swagger\Parameter($operation, 'form', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructNotExclusiveFormConsumes()
	{
		$this->expectException('\SwaggerGen\Exception', "File type 'file' without valid consume");

		$operation = new SwaggerGen\Swagger\Operation($this->parent);
		$operation->handleCommand('consumes', 'text file');
		$parameter = new SwaggerGen\Swagger\Parameter($operation, 'form', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructFormConsumes()
	{
		$operation = new SwaggerGen\Swagger\Operation($this->parent);
		$operation->handleCommand('consumes', 'form');
		$parameter = new SwaggerGen\Swagger\Parameter($operation, 'form', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructFileformConsumes()
	{
		$operation = new SwaggerGen\Swagger\Operation($this->parent);
		$operation->handleCommand('consumes', 'fileform');
		$parameter = new SwaggerGen\Swagger\Parameter($operation, 'form', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');
	}

	/**
	 * @covers \SwaggerGen\Swagger\Type\FileType::__construct
	 */
	public function testConstructBothConsumes()
	{
		$operation = new SwaggerGen\Swagger\Operation($this->parent);
		$operation->handleCommand('consumes', 'fileform form');
		$parameter = new SwaggerGen\Swagger\Parameter($operation, 'form', 'long whatever');
		$object = new SwaggerGen\Swagger\Type\FileType($parameter, 'file');

		$this->assertInstanceOf('\SwaggerGen\Swagger\Type\FileType', $object);

		$this->assertSame(array(
			'type' => 'file',
				), $object->toArray());
	}

}
