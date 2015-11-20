<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Path object, containing any number of operations
 * belonging to a single endpoint defined by this class.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Path extends AbstractObject
{

	/**
	 * @var Operation[] $operation
	 */
	private $Operations = array();

	/**
	 * @var Tag|null $tag;
	 */
	private $Tag;

	public function __construct(AbstractObject $parent, Tag $Tag = null)
	{
		parent::__construct($parent);
		$this->Tag = $Tag;
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'method': // alias
			case 'operation':
				$method = strtolower(\SwaggerGen\Util::words_shift($data));

				if (isset($this->Operations[$method])) {
					$Operation = $this->Operations[$method];
				} else {
					$summary = $data;
					$Operation = new Operation($this, $summary, $this->Tag);
					$this->Operations[$method] = $Operation;
				}

				return $Operation;

			case 'description':
				if ($this->Tag) {
					return $this->Tag->handleCommand($command, $data);
				}
				break;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return \SwaggerGen\Util::array_filter_null(array_merge(
								\SwaggerGen\Util::arrayToArray($this->Operations)
								, parent::toArray())
		);
	}

}
