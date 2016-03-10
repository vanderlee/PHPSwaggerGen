<?php

namespace SwaggerGen\Swagger;

/**
 * Object representing the root level of a Swagger 2.0 document.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Swagger extends AbstractDocumentableObject
{

	private $swagger = '2.0';
	private $host;
	private $basePath;

	/**
	 * @var Info $Info
	 */
	private $Info;
	private $schemes = array();
	private $consumes = array();
	private $produces = array();

	/**
	 * @var Path[] $Paths
	 */
	private $Paths = array();
	private $definitions = array();

	/**
	 * @var Tag[] $Tags
	 */
	private $Tags = array();

	/**
	 * Default tag for new endpoints/operations. Set by the api command.
	 * @var Tag
	 */
	private $defaultTag = null;
	//private $parameters;
	//private $responses;
	private $securityDefinitions = array();
	private $security = array();

	//private $security;

	public function __construct($host = '', $basePath = '')
	{
		parent::__construct(null);

		$this->host = $host;
		$this->basePath = $basePath;

		$this->Info = new Info($this);
	}

	public function getRoot()
	{
		return $this;
	}

	public function getInfo()
	{
		if (!$this->Info) {
			$this->Info;
		}
		return $this->Info;
	}

	public function getConsumes()
	{
		return $this->consumes;
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			// Info.string
			case 'title':
			case 'description':
			case 'version':
			case 'terms': // alias
			case 'tos': // alias
			case 'termsofservice':
			case 'contact':
			case 'license':
				return $this->Info->handleCommand($command, $data);

			// string[]
			case 'scheme':
			case 'schemes':
				$this->$command = array_unique(array_merge($this->$command, self::wordSplit($data)));
				return $this;

			// MIME[]
			case 'consume':
			case 'consumes':
			case 'produce':
			case 'produces':
				$this->$command = array_merge($this->$command, self::translateMimeTypes(self::wordSplit($data)));
				return $this;

			case 'model': // alias
				$data = 'params ' . $data;
			case 'define':
			case 'definition':
				$type = self::wordShift($data);
				switch ($type) {
					case 'response':
//						$definition = new SwaggerResponseDefinition($this);
//						break;
					case 'params':
					case 'parameters': // alias
						$definition = new Schema($this);
						break;

					default:
						throw new \SwaggerGen\Exception('Unsupported definition type: ' . $type);
				}

				$this->definitions[self::wordShift($data)] = $definition;
				return $definition;

			case 'api': // alias
			case 'tag':
				$tagname = self::wordShift($data);

				$Tag = null;
				foreach ($this->Tags as $T) {
					if ($T->getName() === $tagname) {
						$Tag = $T;
						break;
					}
				}
				if (!$Tag) {
					$Tag = new Tag($this, $tagname, $data);
					$this->Tags[] = $Tag;
				}
				// @todo remove this; it's for backwards compatibility only
				if ($command === 'api') { // backwards compatibility
					$this->defaultTag = $Tag;
				}
				return $Tag;

			case 'endpoint':
				$path = self::wordShift($data);
				if ($path{0} !== '/') {
					$path = '/' . $path;
				}

				$Tag = null;
				if (($tagname = self::wordShift($data)) !== false) {
					foreach ($this->Tags as $T) {
						if (strtolower($T->getName()) === strtolower($tagname)) {
							$Tag = $T;
							break;
						}
					}
					if (!$Tag) {
						$Tag = new Tag($this, $tagname, $data);
						$this->Tags[] = $Tag;
					}
				}

				if (!isset($this->Paths[$path])) {
					$this->Paths[$path] = new Path($this, $Tag ? : $this->defaultTag);
				}
				return $this->Paths[$path];

			case 'security':
				$name = self::wordShift($data);
				$type = self::wordShift($data);
				$SecurityScheme = new SecurityScheme($this, $type, $data);
				$this->securityDefinitions[$name] = $SecurityScheme;
				return $SecurityScheme;

			case 'require':
				$name = self::wordShift($data);
				$this->security[$name] = self::wordSplit($data);
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'swagger' => $this->swagger,
					'info' => $this->Info->toArray(),
					'host' => $this->host,
					'basePath' => $this->basePath,
					'schemes' => $this->schemes,
					'consumes' => $this->consumes,
					'produces' => $this->produces,
					'paths' => self::objectsToArray($this->Paths),
					'definitions' => self::objectsToArray($this->definitions),
//					'parameters' => $this->parameters ? $this->parameters->toArray() : null,
//					'responses' => $this->responses ? $this->responses->toArray() : null,
					'securityDefinitions' => self::objectsToArray($this->securityDefinitions),
					'security' => $this->security,
					'tags' => self::objectsToArray($this->Tags),
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}

}
