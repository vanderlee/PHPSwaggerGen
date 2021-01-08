<?php
declare(strict_types=1);

namespace SwaggerGen\Parser;

/**
 * Generic preprocessor statement handler.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractPreprocessor
{

	private $defines = array();
	private $stack = array();

	public function __construct()
	{
		$this->resetDefines();
	}

	public function resetDefines()
	{
		$this->defines = array();
	}

	public function addDefines(array $defines)
	{
		$this->defines = array_merge($this->defines, $defines);
	}

	public function define($name, $value = 1)
	{
		$this->defines[$name] = $value;
	}

	public function undefine($name)
	{
		unset($this->defines[$name]);
	}

	protected function getState()
	{
		return empty($this->stack) || (bool) end($this->stack);
	}

	/**
	 * Get the first word from a string and remove it from the string.
	 *
	 * @param string $data
	 * @return boolean|string
	 */
	private static function wordShift(&$data)
	{
		if (preg_match('~^(\S+)\s*(.*)$~', $data, $matches) === 1) {
			$data = $matches[2];
			return $matches[1];
		}
		return false;
	}

	protected function handle($command, $expression)
	{
		switch (strtolower($command)) {
			case 'if':
				$name = self::wordShift($expression);
				$state = $this->getState();
				if (empty($expression)) {
					$this->stack[] = $state && !empty($this->defines[$name]);
				} else {
					$this->stack[] = $state && isset($this->defines[$name]) && $this->defines[$name] == $expression;
				}
				break;

			case 'ifdef':
				$this->stack[] = $this->getState() && isset($this->defines[$expression]);
				break;

			case 'ifndef':
				$this->stack[] = $this->getState() && !isset($this->defines[$expression]);
				break;

			case 'else':
				$state = $this->getState();
				array_pop($this->stack);
				$this->stack[] = !$state;
				break;

			case 'elif':
				$name = self::wordShift($expression);
				$state = $this->getState();
				array_pop($this->stack);
				if (empty($expression)) {
					$this->stack[] = !$state && !empty($this->defines[$name]);
				} else {
					$this->stack[] = !$state && isset($this->defines[$name]) && $this->defines[$name] == $expression;
				}
				break;

			case 'define':
				$name = self::wordShift($expression);
				$this->defines[$name] = $expression;
				break;

			case 'undef':
				unset($this->defines[$expression]);
				break;

			case 'endif':
				array_pop($this->stack);
				break;

			default:
				return false;
		}

		return true;
	}

	public function preprocess($content)
	{
		$this->stack = array();

		return $this->parseContent($content);
	}

	public function preprocessFile($filename)
	{
		return $this->preprocess(file_get_contents($filename));
	}

	abstract protected function parseContent($content);
}
