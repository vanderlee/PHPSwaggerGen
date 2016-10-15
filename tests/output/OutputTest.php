<?php

class OutputTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @covers \SwaggerGen\Exception::__construct
	 * @dataProvider provideAllCases
	 */
	public function testAllCases($name, $files, $expected)
	{
		$SwaggerGen = new \SwaggerGen\SwaggerGen('example.com', '/base');
		$actual = $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_JSON_PRETTY);
		$this->assertSame($expected, $actual, $name);
	}
	
	public function provideAllCases() {
		$cases = array();
		
		foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {					
			$path = realpath($dir);					
			$cases[] = array(
				basename($dir),
				array(
					$path . '/source.php',
				),
				file_get_contents($path . '/expected.json')
			);
		}
		
		return $cases;
	}

}
