<?php
declare(strict_types=1);

namespace SwaggerGen\Parser;

/**
 * Interface for SwaggerGen parsers.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
interface IParser
{

    public function parse(string $file, array $defines = []): array;
}
