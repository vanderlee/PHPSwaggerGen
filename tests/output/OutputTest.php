<?php

class OutputTest extends SwaggerGen_TestCase
{

	/**
	 * @covers \SwaggerGen\Exception::__construct
	 * @dataProvider provideAllCases
	 */
	public function testAllCases($name, $files, $expected)
	{
		$SwaggerGen = new \SwaggerGen\SwaggerGen('example.com', '/base');
		$actual = $SwaggerGen->getSwagger($files, array(), \SwaggerGen\SwaggerGen::FORMAT_JSON);
		$this->assertSame($expected, $actual, $name);
	}
	
	public function provideAllCases() {
		$cases = array();
		
		foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {					
			$path = realpath($dir);					
			$json = json_encode(json_decode(file_get_contents($path . '/expected.json')));
			
			$files = array();
			if (file_exists($path . '/source.php')) {
				$files[] = $path . '/source.php';
			}
			if (file_exists($path . '/source.txt')) {
				$files[] = $path . '/source.txt';
			}
			
			$cases[] = array(
				basename($dir),
				$files,
				$json
			);
		}
		
		return $cases;
	}

}
