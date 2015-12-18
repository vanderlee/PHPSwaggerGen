<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger SecurityScheme object, containing non-technical details about the
 * documented API.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class SecurityScheme extends AbstractObject
{

	/**
	 * 'basic', 'apikey' or 'oauth2'
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $description;
	private $name;
	private $in;
	private $flow;
	private $authorizationUrl;
	private $tokenUrl;

	/**
	 * Map of scope-name => description
	 * @var []
	 */
	private $scopes = array();

	public function __construct(AbstractObject $parent, $type, $data = null)
	{
		parent::__construct($parent);

		if (!in_array(strtolower($type), array('basic', 'apikey', 'oauth2'))) {
			throw new Exception("Security scheme type must be either 'basic', 'apiKey' or 'oauth2', not '{$type}'");
		}
		$this->type = strtolower($type);

		switch ($this->type) {
			case 'basic':
				$this->description = $data;
				break;

			case 'apikey':
				$this->name = self::words_shift($data);

				$in = strtolower(self::words_shift($data));
				if (!in_array($in, array('query', 'header'))) {
					throw new Exception("ApiKey in must be either 'query' or 'header', not '{$in}'");
				}
				$this->in = $in;

				$this->description = $data;
				break;

			case 'oauth2':
				$flow = strtolower(self::words_shift($data));
				if (!in_array($flow, array('implicit', 'password', 'application', 'accesscode'))) {
					throw new Exception("OAuth2 flow must be either 'implicit', 'password', 'application' or 'accesscode', not '{$flow}'");
				}
				$this->flow = $flow;

				switch ($this->flow) {
					case 'implicit':
						$this->authorizationUrl = self::words_shift($data);
						break;

					case 'password':
						$this->authorizationUrl = self::words_shift($data);
						$this->tokenUrl = self::words_shift($data);
						break;

					case 'application':
						$this->tokenUrl = self::words_shift($data);
						break;

					case 'accesscode':
						$this->tokenUrl = self::words_shift($data);
						break;
				}

				$this->description = $data;
				break;
		}
	}

	public function handleCommand($command, $data = null)
	{
		switch (strtolower($command)) {
			case 'description':
				$this->description = $data;
				return $this;

			case 'scope':
				if ($this->type === 'oauth2') {
					$name = self::words_shift($data);
					$scopes[$name] = $data;
					return $this;
				}
				break;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array_merge(array(
					'type' => $this->type === 'apikey' ? 'apiKey' : $this->type,
					'description' => $this->description,
					'name' => $this->name,
					'in' => $this->in,
					'flow' => $this->flow === 'accesscode' ? 'accessCode' : $this->flow,
					'authorizationUrl' => $this->authorizationUrl,
					'tokenUrl' => $this->tokenUrl,
					'scopes' => $this->scopes,
								), parent::toArray()));
	}

}
