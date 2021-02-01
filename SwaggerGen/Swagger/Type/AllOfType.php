<?php
declare(strict_types=1);

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
	private $allOfItems = [];
	private $mostRecentItem;
	protected function parseDefinition(string $definition): void
	{
		$pattern = self::REGEX_START . 'allof' . self::REGEX_CONTENT . self::REGEX_END;
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

	public function handleCommand($command, $data = null)
	{
		switch ($command) {
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

	public function toArray(): array
	{
		$allOf = [];
		foreach ($this->allOfItems as $item) {
			$allOf[] = $item->toArray();
		}
		return self::arrayFilterNull(array_merge(array(
			'allOf' => $allOf,
		), parent::toArray()));
	}
}
