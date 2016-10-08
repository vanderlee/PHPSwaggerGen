<?php

namespace SwaggerGen\Swagger;

/**
 * Object representing the root level of a Swagger 2.0 document.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2016 Martijn van der Lee
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
	 * @var \SwaggerGen\Swagger\Path[] $Paths
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
	private $securityDefinitions = array();
	private $security = array();

	/**
	 * @inheritDoc
	 * @param string $host
	 * @param string $basePath
	 */
	public function __construct($host = null, $basePath = null)
	{
		parent::__construct(null);

		$this->host = $host;
		$this->basePath = $basePath;

		$this->Info = new Info($this);
	}

	/**
	 * @inheritDoc
	 */
	protected function getRoot()
	{
		return $this;
	}

	/**
	 * Return all consumes
	 * @todo Deprecate in favour of a getConsume($name);
	 * @return string
	 */
	public function getConsumes()
	{
		return $this->consumes;
	}

	/**
	 * Return the named security if it exists. Otherwise return FALSE
	 * @param string $name
	 * @return boolean|SecurityScheme
	 */
	public function getSecurity($name)
	{
		if (isset($this->securityDefinitions[$name])) {
			return $this->securityDefinitions[$name];
		}

		return false;
	}

	/**
	 * @param string $command
	 * @param string $data
	 * @return \SwaggerGen\Swagger\AbstractObject|boolean
	 */
	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			// pass to Info
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
				$this->schemes = array_unique(array_merge($this->schemes, self::wordSplit($data)));
				return $this;

			// MIME[]
			case 'consume':
			case 'consumes':
				$this->consumes = array_merge($this->consumes, self::translateMimeTypes(self::wordSplit($data)));
				return $this;
				
			case 'produce':
			case 'produces':
				$this->produces = array_merge($this->produces, self::translateMimeTypes(self::wordSplit($data)));
				return $this;

			case 'model': // alias
				$data = 'params ' . $data;
			// Fallthrough intentional
			case 'define':
			case 'definition':
				$type = self::wordShift($data);
				switch ($type) {
					case 'params':
					case 'parameters': // alias
						$definition = new Schema($this);
						break;

					default:
						throw new \SwaggerGen\Exception("Unsupported definition type: '{$type}'");
				}

				$name = self::wordShift($data);
				if (empty($name)) {
					throw new \SwaggerGen\Exception('Missing definition name');
				}
				$this->definitions[$name] = $definition;
				return $definition;

			case 'api': // alias
			case 'tag':
				$tagname = self::wordShift($data);
				if (empty($tagname)) {
					throw new \SwaggerGen\Exception('Missing tag name');
				}

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
					$this->Paths[$path] = new Path($this, $Tag ?: $this->defaultTag);
				}
				return $this->Paths[$path];

			case 'security':
				$name = self::wordShift($data);
				if (empty($name)) {
					throw new \SwaggerGen\Exception('Missing security name');
				}
				$type = self::wordShift($data);
				if (empty($type)) {
					throw new \SwaggerGen\Exception('Missing security type');
				}
				$SecurityScheme = new SecurityScheme($this, $type, $data);
				$this->securityDefinitions[$name] = $SecurityScheme;
				return $SecurityScheme;

			case 'require':
				$name = self::wordShift($data);
				if (empty($name)) {
					throw new \SwaggerGen\Exception('Missing require name');
				}
				$scopes = self::wordSplit($data);
				sort($scopes);
				$this->security[] = array(
					$name => empty($scopes) ? array() : $scopes,
				);
				return $this;
		}

		return parent::handleCommand($command, $data);
	}

	/**
	 * @inheritDoc
	 */
	public function toArray()
	{
		if (empty($this->Paths)) {
			throw new \SwaggerGen\Exception('No path defined');
		}


		$schemes = array_unique($this->schemes);
		sort($schemes);

		$consumes = array_unique($this->consumes);
		sort($consumes);

		$produces = array_unique($this->produces);
		sort($produces);

		foreach ($this->security as $security) {
			foreach ($security as $name => $scopes) {
				if (!isset($this->securityDefinitions[$name])) {
					throw new \SwaggerGen\Exception("Required security scheme not defined: '{$name}'");
				}
			}
		}

		return self::arrayFilterNull(array_merge(array(
					'swagger' => $this->swagger,
					'info' => $this->Info->toArray(),
					'host' => empty($this->host) ? null : $this->host,
					'basePath' => empty($this->basePath) ? null : $this->basePath,
					'consumes' => $consumes,
					'produces' => $produces,
					'schemes' => $schemes,
					'paths' => self::objectsToArray($this->Paths),
					'definitions' => self::objectsToArray($this->definitions),
					'securityDefinitions' => self::objectsToArray($this->securityDefinitions),
					'security' => $this->security,
					'tags' => self::objectsToArray($this->Tags),
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__;
	}
	
	/**
	 * Return a reference string for the named reference by looking it up in the
	 * various definitions
	 * 
	 * @param string $name
	 * @returnstring
	 */
	public function resolveReference($name) {
		if (isset($this->definitions[$name])) {
			return '#/definitions/' . $name;
		} else {
			throw new \SwaggerGen\Exception("No reference definition found for '{$name}'");
		}
	}

}
