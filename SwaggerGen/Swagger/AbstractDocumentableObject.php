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
	 * @var ExternalDoc
	 */
	private $externalDocs = null;

	/**
	 * @param string $command
	 * @param string $data
	 * @return AbstractObject
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'doc':
			case 'docs':
				$url = self::words_shift($data);
				$this->externalDocs = new ExternalDoc($this, $url, $data);
				return $this->externalDocs;
		}

		return parent::handleCommand($command, $data);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return self::array_filter_null(array_merge(array(
					'externalDocs' => $this->externalDocs ? $this->externalDocs->toArray() : null,
								), parent::toArray()
		));
	}

}
