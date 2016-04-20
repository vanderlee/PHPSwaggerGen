<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Operation object, which describes a method call of a
 * specific endpoint.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Operation extends AbstractDocumentableObject
{

	private $tags = array();
	private $summary;
	private $description;
	private $operationid;
	private $consumes = array();
	private $produces = array();

	/**
	 * @var IParameter[]
	 */
	private $parameters = array();
	private $responses = array();
	private $schemes = array();
	private $deprecated = false;
	private $security = array();

	public function getConsumes()
	{
		return $this->consumes;
	}

	public function __construct(AbstractObject $parent, $summary = null, Tag $tag = null)
	{
		parent::__construct($parent);
		$this->summary = $summary;
		$this->operationid = uniqid('', true); //@todo getSwagger()->title -> Do some complex construction?
		if ($tag) {
			$this->tags[] = $tag->getName();
		}
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			// string
			case 'summary':
			case 'description':
				$this->$command = $data;
				return $this;

			// string[]
			case 'tags':
			case 'schemes':
				$this->$command = array_merge($this->$command, self::wordSplit($data));
				return $this;

			// MIME[]
			case 'consumes':
			case 'produces':
				$this->$command = array_merge($this->$command, self::translateMimeTypes(self::wordSplit($data)));
				return $this;

			// boolean
			case 'deprecated':
				$this->deprecated = true;
				return $this;

			case 'error':
				$code = self::wordShift($data);
				$reasoncode = Response::getCode($code);
				if ($reasoncode === null) {
					throw new \SwaggerGen\Exception("Invalid error code: '$code'");
				}
				$description = $data;
				$Error = new Error($this, $reasoncode, $description);
				$this->responses[$reasoncode] = $Error;
				return $Error;

			case 'errors':
				foreach (self::wordSplit($data) as $code) {
					$reasoncode = Response::getCode($code);
					if ($reasoncode === null) {
						throw new \SwaggerGen\Exception("Invalid error code: '$code'");
					}
					$this->responses[$reasoncode] = new Error($this, $reasoncode);
				}
				return $this;

			case 'path':
			case 'query': case 'query?':
			case 'header': case 'header?':
			case 'form': case 'form?':
				$in = rtrim($command, '?');
				$Parameter = new Parameter($this, $in, $data, substr($command, -1) !== '?');
				$this->parameters[] = $Parameter;
				return $Parameter;

			case 'body': case 'body?':
				$Parameter = new BodyParameter($this, $data, substr($command, -1) !== '?');
				$this->parameters[] = $Parameter;
				return $Parameter;

			case 'response':
				$code = self::wordShift($data);
				$reasoncode = Response::getCode($code);
				if ($reasoncode === null) {
					throw new \SwaggerGen\Exception("Invalid response code: '$code'");
				}
				$definition = self::wordShift($data);
				$description = $data;
				$Response = new Response($this, $reasoncode, $definition, $description);
				$this->responses[$reasoncode] = $Response;
				return $Response;

			case 'require':
				$name = self::wordShift($data);
				if (empty($name)) {
					throw new \SwaggerGen\Exception('Empty security requirement name');
				}
				$scopes = self::wordSplit($data);
				sort($scopes);
				$this->security[$name] = empty($scopes) ? (object) null : $scopes;
				return $this;

			//@todo operationId			
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		if (empty($this->responses)) {
			throw new \SwaggerGen\Exception('No response defined for operation');
		}
		ksort($this->responses);

		$tags = array_unique($this->tags);
		sort($tags);

		$schemes = array_unique($this->schemes);
		sort($schemes);

		$consumes = array_unique($this->consumes);
		sort($consumes);

		$produces = array_unique($this->produces);
		sort($produces);

		foreach ($this->security as $name => $scopes) {
			if ($this->getRoot()->getSecurity($name) === false) {
				throw new \SwaggerGen\Exception("Required security scheme not defined: '{$name}'");
			}
		}

		return self::arrayFilterNull(array_merge(array(
					'deprecated' => $this->deprecated ? true : null,
					'tags' => $tags,
					'summary' => empty($this->summary) ? null : $this->summary,
					'description' => empty($this->description) ? null : $this->description,
					//'operationId' => $this->description,
					'consumes' => $consumes,
					'produces' => $produces,
					'parameters' => $this->parameters ? self::objectsToArray($this->parameters) : null,
					'schemes' => $schemes,
					'responses' => $this->responses ? self::objectsToArray($this->responses) : null,
					'security' => $this->security,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->summary;
	}

}
