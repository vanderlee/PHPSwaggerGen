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
	private $deprecated; // bool
	private $security; // SwaggerSecurity

	public function getConsumes()
	{
		return $this->consumes;
	}

	public function __construct(AbstractObject $parent, $summary = '', Tag $tag = null)
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
				$code = Response::getCode(self::wordShift($data));
				$description = $data;
				$Error = new Error($this, $code, $description);
				$this->responses[$code] = $Error;
				return $Error;

			case 'errors':
				foreach (self::wordSplit($data) as $code) {
					$code = Response::getCode($code);
					$this->responses[$code] = new Error($this, $code);
				}
				return $this;

			case 'path': case 'path?':
			case 'query': case 'query?':
			case 'header': case 'header?':
			case 'form': case 'form?':
				$in = rtrim($command, '?');
				$Parameter = new Parameter($this, $in, $data, substr($command, -1) !== '?');
				$this->parameters[] = $Parameter;
				return $Parameter;

			case 'body': case 'body?':
				$in = rtrim($command, '?');
				$Parameter = new BodyParameter($this, $data, substr($command, -1) !== '?');
				$this->parameters[] = $Parameter;
				return $Parameter;

			case 'response':
				$code = Response::getCode(strtolower(self::wordShift($data)));
				$definition = self::wordShift($data);
				$description = $data;
				$Response = new Response($this, $code, $definition, $description);
				$this->responses[$code] = $Response;
				return $Response;

			case 'require':
				$name = self::wordShift($data);
				$this->security[$name] = self::wordSplit($data);
				return $this;

			//@todo operationId			
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::arrayFilterNull(array_merge(array(
					'tags' => $this->tags,
					'summary' => $this->summary,
					'description' => $this->description,
					//'operationId' => $this->description,
					'consumes' => $this->consumes,
					'produces' => $this->produces,
					'parameters' => $this->parameters ? self::objectsToArray($this->parameters) : null,
					'responses' => $this->responses ? self::objectsToArray($this->responses) : null,
					'schemes' => $this->schemes,
					'security' => $this->security,
								), parent::toArray()));
	}

	public function __toString()
	{
		return __CLASS__ . ' ' . $this->summary;
	}

}
