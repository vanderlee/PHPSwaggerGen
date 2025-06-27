<?php

namespace SwaggerGen\Parser\Php;

use SwaggerGen\Parser\AbstractPreprocessor;

/**
 * Preprocessor parser for PHP files.
 *
 * Parses all PHP comments and removes content conditionally according to the
 * rules of the AbstractPreprocessor.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2017 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Preprocessor extends AbstractPreprocessor
{

    private $prefix = 'rest';

    public function __construct($prefix = null)
    {
        parent::__construct();
        if (!empty($prefix)) {
            $this->prefix = $prefix;
        }
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    protected function parseContent($content)
    {
        $pattern = '/@' . preg_quote($this->getPrefix(), '/') . '\\\\([a-z]+)\\s*(.*)$/';

        $output_file = '';

        foreach (token_get_all($content) as $token) {
            $output = '';

            if (is_array($token)) {
                switch ($token[0]) {
                    case T_DOC_COMMENT:
                    case T_COMMENT:
                        foreach (preg_split('/(\\R)/m', $token[1], -1, PREG_SPLIT_DELIM_CAPTURE) as $index => $line) {
                            if ($index % 2) {
                                $output .= $line;
                            } else {
                                $match = array();
                                if (preg_match($pattern, $line, $match) === 1) {
                                    if (!$this->handle($match[1], $match[2]) && $this->getState()) {
                                        $output .= $line;
                                    } else {
                                        $output .= str_replace('@' . $this->getPrefix() . '\\', '@!' . $this->getPrefix() . '\\', $line);
                                    }
                                } else {
                                    $output .= $line;
                                }
                            }
                        }
                        break;

                    default:
                        $output .= $token[1];
                }
            } else {
                $output .= $token;
            }

            if ($this->getState()) {
                $output_file .= $output;
            } else {
                $output_file .= '/* ' . $output . ' */';
            }
        }

        return $output_file;
    }

}
