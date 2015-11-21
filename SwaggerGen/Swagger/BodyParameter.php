<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Parameter object of the "body" variety.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class BodyParameter extends AbstractObject implements IParameter
{

	private $name = '';
	private $description;
	private $required = false;

	/**
	 * @var Schema
	 */
	private $Schema;

	public function __construct(AbstractObject $parent, $data, $required = false)
	{
		parent::__construct($parent);

		$type = self::words_shift($data);
		$this->name = self::words_shift($data);
		$this->description = $data;
		$this->required = (bool) $required;

		$this->Schema = new Schema($this, $type);
	}

	public function handleCommand($command, $data = null)
	{
		// offload to schema
		$return = $this->Schema->handleCommand($command, $data);
		if ($return) {
			return $return;
		}

		switch (strtolower($command)) {
			// nothing yet!
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array_merge([
					'name' => $this->name,
					'in' => 'body',
					'description' => $this->description,
					'required' => $this->required ? 'true' : null,
					'schema' => $this->Schema->toArray(),
								], parent::toArray()));
	}

}
