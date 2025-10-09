<?php

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;

/**
 * Describes a Swagger Operation object, which describes a method call of a
 * specific endpoint.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2025 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Operation extends AbstractDocumentableObject
{

    private $tags = [];
    private $summary;
    private $description;
    private $consumes = [];
    private $produces = [];

    /**
     * @var IParameter[]
     */
    private $parameters = [];
    private $responses = [];
    private $schemes = [];
    private $deprecated = false;
    private $security = [];

    /**
     * @var string
     */
    private $operationId;

    /**
     * @param string $summary
     */
    public function __construct(AbstractObject $parent, $summary = null, ?Tag $tag = null)
    {
        parent::__construct($parent);
        $this->summary = $summary;
        if ($tag) {
            $this->tags[] = $tag->getName();
        }
    }

    public function getConsumes(): array
    {
        return $this->consumes;
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     * @throws Exception
     */
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
                    throw new Exception("Invalid error code: '$code'");
                }
                $description = $data;
                $Error = new Error($this, $reasoncode, $description);
                $this->responses[$reasoncode] = $Error;
                return $Error;

            case 'errors':
                foreach (self::wordSplit($data) as $code) {
                    $reasoncode = Response::getCode($code);
                    if ($reasoncode === null) {
                        throw new Exception("Invalid error code: '$code'");
                    }
                    $this->responses[$reasoncode] = new Error($this, $reasoncode);
                }
                return $this;

            case 'path':
            case 'query':
            case 'query?':
            case 'header':
            case 'header?':
            case 'form':
            case 'form?':
                $in = rtrim($command, '?');
                $parameter = new Parameter($this, $in, $data, !str_ends_with($command, '?'));
                $this->parameters[$parameter->getName()] = $parameter;
                return $parameter;

            case 'body':
            case 'body?':
                $parameter = new BodyParameter($this, $data, !str_ends_with($command, '?'));
                $this->parameters[$parameter->getName()] = $parameter;
                return $parameter;

            case 'param':
            case 'parameter':
                $parameter = new ParameterReference($this, $data);
                $this->parameters[$parameter->getName()] = $parameter;
                return $this;

            case 'response':
                $code = self::wordShift($data);
                $reasoncode = Response::getCode($code);
                if ($reasoncode === null) {
                    $reference = $code;
                    $code = self::wordShift($data);
                    $reasoncode = Response::getCode($code);
                    if ($reasoncode === null) {
                        throw new Exception("Invalid response code: '$reference'");
                    }
                    $this->responses[$reasoncode] = new ResponseReference($this, $reference);
                    return $this;
                }

                $definition = self::wordShift($data);
                $description = $data;
                $Response = new Response($this, $reasoncode, $definition, $description);
                $this->responses[$reasoncode] = $Response;
                return $Response;

            case 'require':
                $name = self::wordShift($data);
                if (empty($name)) {
                    throw new Exception('Empty security requirement name');
                }
                $scopes = self::wordSplit($data);
                sort($scopes);
                $this->security[] = array(
                    $name => empty($scopes) ? [] : $scopes,
                );
                return $this;

            case 'id':
                $operationId = self::trim($data);
                if ($this->getSwagger()->hasOperationId($operationId)) {
                    throw new Exception("Duplicate operation id '{$operationId}'");
                }
                $this->operationId = $operationId;
                return $this;
        }

        return parent::handleCommand($command, $data);
    }

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        if (empty($this->responses)) {
            throw new Exception('No response defined for operation');
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

        foreach ($this->security as $security) {
            foreach ($security as $name => $scope) {
                if ($this->getSwagger()->getSecurity($name) === false) {
                    throw new Exception("Required security scheme not defined: '{$name}'");
                }
            }
        }

        $parameters = $this->parameters ? array_values($this->parameters) : null;

        return self::arrayFilterNull(array_merge(array(
            'deprecated' => $this->deprecated ? true : null,
            'tags' => $tags,
            'summary' => empty($this->summary) ? null : $this->summary,
            'description' => empty($this->description) ? null : $this->description,
            'operationId' => $this->operationId,
            'consumes' => $consumes,
            'produces' => $produces,
            'parameters' => $parameters ? self::objectsToArray($parameters) : null,
            'schemes' => $schemes,
            'responses' => $this->responses ? self::objectsToArray($this->responses) : null,
            'security' => $this->security,
        ), parent::toArray()));
    }

    /**
     * Return the operation ID
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->operationId;
    }

    public function __toString()
    {
        return __CLASS__ . ' ' . $this->summary;
    }

}
