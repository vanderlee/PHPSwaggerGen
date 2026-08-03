<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SwaggerGen\Parser\Php\Preprocessor as PhpPreprocessor;
use SwaggerGen\Parser\Text\Preprocessor as TextPreprocessor;

class VariableExpansionTest extends TestCase
{
    public function testPhpPreprocessorExpandsDefinedValues(): void
    {
        $preprocessor = new PhpPreprocessor();
        $preprocessor->define('api_version', '2.4.1');

        $source = "<?php\n/** @rest\\version {api_version} */\n";

        $this->assertSame(
            "<?php\n/** @rest\\version 2.4.1 */\n",
            $preprocessor->preprocess($source)
        );
    }

    public function testTextPreprocessorExpandsDefinedValues(): void
    {
        $preprocessor = new TextPreprocessor();
        $preprocessor->define('api_version', '2.4.1');

        $this->assertSame(
            'Version 2.4.1',
            $preprocessor->preprocess('Version {api_version}')
        );
    }

    public function testUndefinedValuesRemainVisible(): void
    {
        $preprocessor = new TextPreprocessor();

        $this->assertSame(
            'Version {api_version}',
            $preprocessor->preprocess('Version {api_version}')
        );
    }
}
