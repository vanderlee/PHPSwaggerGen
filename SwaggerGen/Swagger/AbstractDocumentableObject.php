<?php

namespace SwaggerGen\Swagger;

/**
 * Abstract parent for all Swagger objects that may have external documentation
 * attached.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
abstract class AbstractDocumentableObject extends AbstractObject
{

	/**
	 * External documentation
	 * @var ExternalDocumentation
	 */
	private $externalDocs = null;

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'doc':
			case 'docs':
				$url = self::wordShift($data);
				$this->externalDocs = new ExternalDocumentation($this, $url, $data);
				return $this->externalDocs;
		}

		return parent::handleCommand($command, $data);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'externalDocs' => $this->externalDocs ? $this->externalDocs->toArray() : null,
								), parent::toArray()
		));
	}

}
