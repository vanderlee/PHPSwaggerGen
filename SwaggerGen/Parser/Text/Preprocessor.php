<?php
declare(strict_types=1);

namespace SwaggerGen\Parser\Text;

use SwaggerGen\Parser\AbstractPreprocessor;

/**
 * Preprocessor parser for Text files.
 *
 * Parses all text lines and removes content conditionally according to the
 * rules of the AbstractPreprocessor.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Preprocessor extends AbstractPreprocessor
{

    /**
     * @param string $content
     *
     * @return string
     */
	protected function parseContent(string $content): string
	{
		$pattern = '/\\s*([a-z]+)\\s*(.*)\\s*/';

		$output = '';

		foreach (preg_split('/(\\R)/m', $content, -1, PREG_SPLIT_DELIM_CAPTURE) as $index => $line) {
			if ($index % 2) {
				$output .= $line;
			} else {
				$match = [];
				if (preg_match($pattern, $line, $match) === 1) {
					if (!$this->handle($match[1], $match[2]) && $this->getState()) {
						$output .= $line;
					} else {
						$output .= '';
					}
				} else {
					$output .= $line;
				}
			}
		}

		return $output;
	}

}
