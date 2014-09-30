<?php

/**
 * SwaggerGen
 * @copyright 2014 Martijn W. van der Lee
 * @license MIT
 * @author Martijn W. van der Lee
 *
 * @todo resource command, to restart on resource
 * @todo subset support for repeatable insertion
 * @todo Errors by reason or shortcode
 * @todo param/prop modifiers
 * @todo OAuth2 support
 * @todo method/endpoint/api inheritance (from abstract models)
 * @todo optimize models; only include used models
 */
class SwaggerGen {
	/**
	 * List of filenames or text fragments
	 * @var array
	 */
	private $sources = array();

	private $basePath;

	/**
	 * @var \SwaggerAbstractScope
	 */
	private $current;

	/**
	 * @var \SwaggerResource
	 */
	private $Resource;

	/**
	 * Model currently handling
	 * @var \SwaggerModel
	 */
	private $currentModel;

	/**
	 * Primitive currently handling
	 * @var \SwaggerAbstractPrimitive
	 */
	private $currentPrimitive;

	/**
	 * Create a new SwaggerGen instance
	 * @param array $sources array of filenames or text fragments
	 * @param string $basePath base URL path of API endpoints
	 */
	public function __construct($sources = array(), $basePath = null) {
		$this->addSources($sources);
		$this->setBasePath($basePath);
	}

	/**
	 * Add a new source
	 * @param string $source filename or text fragment
	 */
	public function addSource($source) {
		$this->sources[] = $source;
	}

	/**
	 * Add new sources
	 * @param array $sources array of filenames or text fragments
	 */
	public function addSources($sources) {
		foreach ((array)$sources as $source) {
			$this->addSource($source);
		}
	}

	/**
	 * Set the base URL path of all API endpoints
	 * @param string $basePath base URL path of API endpoints
	 */
	public function setBasePath($basePath) {
		$this->basePath = $basePath;
	}


	/**
	 * Shifts the first word off a text line and returns it
	 * @param string $line
	 * @return string
	 */
	private static function shift(&$line) {
		preg_match('~^(\S+)\s*(.*)$~', $line, $matches);
		$line = $matches[2];
		return $matches[1];
	}

	/**
	 * Splits a text line in all it's words
	 * @param string $line
	 * @return string
	 */
	private static function words($line) {
		return preg_split('~\s+~', $line);
	}

	/**
	 * Get lines of text from a source, either filename or text fragment
	 * @param string $source filename or text fragment
	 * @return array of text lines
	 */
	private function parseSource($source) {
		$lines = file($source);

		switch (pathinfo($source, PATHINFO_EXTENSION)) {
			case 'php':
				$statements = SwaggerParseStrategyPhp::parse($lines);
				break;

			case 'txt':
				$statements = SwaggerParseStrategyText::parse($lines);
				break;

			default:
				$statements = SwaggerParseStrategyGeneric::parse($lines);
				break;
		}

		foreach ($statements as $statement) {
			$matches = null;
			if (preg_match('~^(\w+)([?+*]?)(?:\\s+(.*))?$~', $statement, $matches) === 1) {
				$command		= $matches[1];
				$multiplicity	= $matches[2];
				$argument		= isset($matches[3]) ? trim($matches[3]) : null;
				call_user_func_array(array($this, 'parseStatement'), array($command, $multiplicity, $argument));
			}
		}
	}

	/**
	 * Parse a single statement
	 * @param string $command the command
	 * @param string $multiplicity single character '?', '+', '*' or empty indicating the repetitions
	 * @param string $argument argument line of the command
	 * @throws Exception
	 */
	private function parseStatement($command, $multiplicity, $argument) {
		switch ($command) {
			case 'api':
				$name		= self::shift($argument);

				$this->Resource	= $this->current->getByClass('SwaggerResource');
				$this->current	= $this->Resource->getApi($name) ?: new SwaggerApi($this->Resource, $name, $argument);
				break;

			case 'endpoint':
				$path		= self::shift($argument);

				$Api		= $this->current->getByClass('SwaggerAbstractApi');
				$this->current	= $Api->getEndpoint($path) ?: new SwaggerEndpoint($Api, $path, $argument);
				break;

			case 'method':
				$method		= self::shift($argument);
				$this->current	= new SwaggerMethod($this->current->getByClass('SwaggerEndpoint'), $method, $argument);
				break;

			case 'model':
				$name			= self::shift($argument);
				$this->curent_model	= new SwaggerModel($this->current->getByProperty('Models'), $name, $argument);
				if ($multiplicity === '+' || $multiplicity === '') {
					$this->curent_model->required = true;
				}
				break;

			case 'property':
				$primitive			= self::shift($argument);
				$name				= self::shift($argument);
				$this->currentPrimitive	= new SwaggerProperty($this->curent_model, $primitive, $name, $argument);
				break;

			case SwaggerParameter::PARAMTYPE_BODY:
			case SwaggerParameter::PARAMTYPE_FORM:
			case SwaggerParameter::PARAMTYPE_HEADER:
			case SwaggerParameter::PARAMTYPE_PATH:
			case SwaggerParameter::PARAMTYPE_QUERY:
				$primitive			= self::shift($argument);
				$name				= self::shift($argument);
				$this->currentPrimitive	= new SwaggerParameter($this->current->getByProperty('Parameters'), $command, $primitive, $name, $argument);
				if ($multiplicity === '+' || $multiplicity === '') {
					$this->currentPrimitive->required = true;
				}
				if ($multiplicity === '+' || $multiplicity === '*') {
					$this->currentPrimitive->allowmultiple = true;
				}
				break;

			case 'error':
				$code			= self::shift($argument);
				$this->current	= new SwaggerError($this->current->getByProperty('Errors'), $code, $argument);
				break;

			case 'errors':
				$ErrorsContainer = $this->current->getByProperty('Errors');
				foreach (self::words($argument) as $code) {
					$this->current	= new SwaggerError($ErrorsContainer, $code);
				}
				break;

			// single properties
			case 'apiversion':			// resource (-> api)
			case 'swaggerversion':		// resource (-> api)
			case 'title':				// resource
			case 'description':			// resource & endpoint //@todo should also parameter
			case 'termsofserviceurl':	// resource
			case 'contact':				// resource
			case 'license':				// resource
			case 'licenseurl':			// resource
			case 'basepath':			// api (base)
			case 'resourcepath':		// api (base)
			case 'notes':				// method
				$this->current->getByProperty($command)->{$command} = $argument;
				break;

			// Boolean if present; false only if 'false'
			case 'deprecated':
				$this->current->getByProperty($command)->{$command} = $argument !== 'false';
				break;

			// append properties or primitive tree
			case 'enum':
				foreach (self::words($argument) as $word) {
					$this->currentPrimitive->getByProperty($command)->{$command}[]	= $word;
				}
				break;

			case 'items':				// parameter.items
			case 'default':				// parameter.default
				$this->currentPrimitive->getByProperty($command)->{$command}	= $argument;
				break;

			// append properties on base tree
			case 'produces':
			case 'consumes':
				foreach (self::words($argument) as $word) {
					$this->current->getByProperty($command)->{$command}[]	= $word;
				}
				break;

			case 'include':
				$this->parseSource($this->basedir.DIRECTORY_SEPARATOR.$argument);
				break;
		}
	}

	private $basedir = null;

	/**
	 * Process the sources and return an array (map) of swagger files.
	 * The first key is always 'resources', corresponding to the resources
	 * JSON. The other keys bear the name of the API they represent.
	 * @return array
	 */
	public function process() {
		$this->current = $this->Resource = new SwaggerResource();
		$this->Resource->basepath	= $this->basePath;

		foreach ($this->sources as $source) {
			$this->basedir = dirname($source);
			$this->parseSource($source);
		}

		$result = array(
			'resources' => $this->Resource->toArray()
		);
		foreach ($this->Resource->Apis as $Api) {
			$result[$Api->name] = $Api->toArray();
		}

		return $result;
	}
}
