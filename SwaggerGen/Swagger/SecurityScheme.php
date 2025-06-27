<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

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

    /**
     * Create a new SecurityScheme object
     * @param AbstractObject $parent
     * @param string $type
     * @param string $data
     * @throws Exception
     */
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
                $this->name = self::wordShift($data);

                $in = strtolower(self::wordShift($data));
                if (!in_array($in, array('query', 'header'))) {
                    throw new Exception("ApiKey in must be either 'query' or 'header', not '{$in}'");
                }
                $this->in = $in;

                $this->description = $data;
                break;

            case 'oauth2':
                $flow = strtolower(self::wordShift($data));
                if (!in_array($flow, array('implicit', 'password', 'application', 'accesscode'))) {
                    throw new Exception("OAuth2 flow must be either 'implicit', 'password', 'application' or 'accesscode', not '{$flow}'");
                }
                $this->flow = $flow;

                if (in_array($flow, array('implicit', 'accesscode'))) {
                    $authUrl = self::wordShift($data);
                    if (!filter_var($authUrl, FILTER_VALIDATE_URL)) {
                        throw new Exception("OAuth2 authorization URL invalid: '{$authUrl}'");
                    }
                    $this->authorizationUrl = $authUrl;
                }

                if (in_array($flow, array('password', 'application', 'accesscode'))) {
                    $tokenUrl = self::wordShift($data);
                    if (!filter_var($tokenUrl, FILTER_VALIDATE_URL)) {
                        throw new Exception("OAuth2 token URL invalid: '{$tokenUrl}'");
                    }
                    $this->tokenUrl = $tokenUrl;
                }

                $this->description = $data;
                break;
        }
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     * @throws Exception
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'description':
                $this->description = $data;
                return $this;

            case 'scope':
                if ($this->type !== 'oauth2') {
                    throw new Exception("Cannot set scope on type '{$this->type}'");
                }

                $name = self::wordShift($data);
                $this->scopes[$name] = $data;
                return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'type' => $this->type === 'apikey' ? 'apiKey' : $this->type,
            'description' => empty($this->description) ? null : $this->description,
            'name' => $this->name,
            'in' => $this->in,
            'flow' => $this->flow === 'accesscode' ? 'accessCode' : $this->flow,
            'authorizationUrl' => $this->authorizationUrl,
            'tokenUrl' => $this->tokenUrl,
            'scopes' => $this->scopes,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__ . ' ' . $this->type;
    }

}
