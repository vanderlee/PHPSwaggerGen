<?php

namespace SwaggerGen\Swagger\Type;

/**
 * allOf compound type
 *
 * @package    SwaggerGen
 * @author     Bruce Weirdan <weirdan@gmail.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class AllOfType extends AbstractType
{
	private $allOfItems = array();
	private $mostRecentItem;
	protected function parseDefinition($definition)
	{
		$pattern = self::REGEX_START . 'allOf' . self::REGEX_CONTENT . self::REGEX_END;
		$inlineDef = '';
		if (preg_match($pattern, $definition, $matches)) {
			if (isset($matches[1])) {
				$inlineDef = $matches[1];
			}
		}
		if ($inlineDef) {
			foreach ($this->parseList($inlineDef) as $item) {
				$this->handleCommand('item', $item);
			}
		}
	}

	/**
	 * @param string $list
	 * @return string
	 * @todo remove duplication between this method and ObjectType::extract_property()
	 */
	private function getItem(&$list)
	{
		static $openingBraces = array('(', '[', '{', '<',);
		static $closingBraces = array(')', ']', '}', '>');

		$depth = 0;
		$index = 0;
		$item = '';

		while ($index < strlen($list)) {
			$c = $list{$index};
			$index++;
			if (in_array($c, $openingBraces)) {
				$depth++;
			} elseif (in_array($c, $closingBraces)) {
				$depth--;
			} elseif ($c === ',' && !$depth) {
				break;
			}
			$item .= $c;
		}
		$list = substr($list, $index);
		return $item;
	}

	/**
	 * @param string $list
	 * @return array
	 */
	private function parseList($list)
	{
		$ret = array();
		while ($item = $this->getItem($list)) {
			$ret[] = $item;
		}
		return $ret;
	}

	public function handleCommand($command, $data = null)
	{
		switch ($command) {
			case 'ref':
			case 'inline':
			case 'item':
				$this->mostRecentItem = self::typeFactory($this, $data);
				$this->allOfItems[] = $this->mostRecentItem;
				return $this;
		}
		if (isset($this->mostRecentItem)) {
			if ($this->mostRecentItem->handleCommand($command, $data)) {
				return $this;
			}
		}
		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		$allOf = array();
		foreach ($this->allOfItems as $item) {
			$allOf[] = $item->toArray();
		}
		return self::arrayFilterNull(array_merge(array(
			'allOf' => $allOf,
		), parent::toArray()));
	}
}
