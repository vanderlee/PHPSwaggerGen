<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\SwaggerGen;

class OutputTest extends TestCase
{

    /**
     * @covers       \SwaggerGen\Exception::__construct
     * @dataProvider provideAllCases
     */
    public function testAllCases($name, $files, $expected)
    {
        $SwaggerGen = new SwaggerGen('example.com', '/base');
        $actual = $SwaggerGen->getSwagger($files, array(), SwaggerGen::FORMAT_JSON);
        $this->assertJsonStringEqualsJsonString($expected, $this->normalizeJson($actual), $name);
    }

    /**
     * Normalizes and pretty-prints json (whitespace mostly)
     *
     * This is useful to get better diff results when assertions fail.
     *
     * @param string $json
     * @return string
     */
    private function normalizeJson($json)
    {
        return json_encode(
            json_decode($json),
            PHP_VERSION_ID >= 50400 ? constant('JSON_PRETTY_PRINT') : 0
        );
    }

    public function provideAllCases()
    {
        $cases = array();

        foreach (glob(__DIR__ . '/*', GLOB_ONLYDIR) as $dir) {
            $path = realpath($dir);
            $json = $this->normalizeJson(file_get_contents($path . '/expected.json'));

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
