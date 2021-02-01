<?php
declare(strict_types=1);

namespace SwaggerGen\Swagger;

use SwaggerGen\Exception;
use SwaggerGen\TypeRegistry;

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
     * @var TypeRegistry
     */
    private $typeRegistry;

    /**
     * @var Info $Info
     */
    private $info;
    private $schemes = [];
    private $consumes = [];
    private $produces = [];

    /**
     * @var Path[] $Paths
     */
    private $paths = [];

    /**
     * @var Schema[] $definitions
     */
    private $definitions = [];

    /**
     * @var IParameter[] $parameters
     */
    private $parameters = [];

    /**
     * @var Response[] $responses
     */
    private $responses = [];

    /**
     * @var Tag[] $Tags
     */
    private $tags = [];

    /**
     * Default tag for new endpoints/operations. Set by the api command.
     *
     * @var Tag
     */
    private $defaultTag = null;
    private $securityDefinitions = [];
    private $security = [];

    /**
     * @param string       $host
     * @param string       $basePath
     * @param TypeRegistry $typeRegistry
     */
    public function __construct($host = null, $basePath = null, $typeRegistry = null)
    {
        parent::__construct();

        $this->host = $host;
        $this->basePath = $basePath;

        $this->info = new Info($this);

        $this->typeRegistry = $typeRegistry ? $typeRegistry : new TypeRegistry;
    }

    /**
     * @inheritDoc
     */
    protected function getSwagger(): Swagger
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getTypeRegistry(): TypeRegistry
    {
        return $this->typeRegistry;
    }

    /**
     * Return all consumes
     *
     * @return array
     * @todo Deprecate in favour of a getConsume($name);
     */
    public function getConsumes()
    {
        return $this->consumes;
    }

    /**
     * Return the named security if it exists. Otherwise return FALSE
     *
     * @param string $name
     *
     * @return boolean|SecurityScheme
     */
    public function getSecurity(string $name)
    {
        if (isset($this->securityDefinitions[$name])) {
            return $this->securityDefinitions[$name];
        }

        return false;
    }

    /**
     * @param string $command
     * @param string $data
     *
     * @return AbstractObject|boolean
     * @throws Exception
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
                return $this->info->handleCommand($command, $data);

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

            case 'model':
            case 'model!':
            case 'definition':
            case 'definition!':
                $name = self::wordShift($data);
                if (empty($name)) {
                    throw new Exception('Missing definition name');
                }
                $typeDef = self::wordShift($data);
                if (empty($typeDef)) {
                    $typeDef = 'object';
                }

                $definition = new Schema($this, $typeDef);
                if (substr($command, -1) === '!') {
                    $definition->setReadOnly();
                }
                $this->definitions[$name] = $definition;

                return $definition;

            case 'path':
            case 'query':
            case 'query?':
            case 'header':
            case 'header?':
            case 'form':
            case 'form?':
                $in = rtrim($command, '?');
                $Parameter = new Parameter($this, $in, $data, substr($command, -1) !== '?');
                $this->parameters[$Parameter->getName()] = $Parameter;

                return $Parameter;

            case 'body':
            case 'body?':
                $Parameter = new BodyParameter($this, $data, substr($command, -1) !== '?');
                $this->parameters[$Parameter->getName()] = $Parameter;

                return $Parameter;

            case 'response':
                $name = self::wordShift($data);
                $definition = self::wordShift($data);
                $description = $data;
                if (empty($description)) {
                    throw new Exception('Response definition missing description');
                }
                $Response = new Response($this, $name, $definition === 'null' ? null : $definition, $description);
                $this->responses[$name] = $Response;

                return $Response;

            case 'api': // alias
            case 'tag':
                $tagname = self::wordShift($data);
                if (empty($tagname)) {
                    throw new Exception('Missing tag name');
                }

                $Tag = null;
                foreach ($this->tags as $T) {
                    if ($T->getName() === $tagname) {
                        $Tag = $T;
                        break;
                    }
                }
                if (!$Tag) {
                    $Tag = new Tag($this, $tagname, $data);
                    $this->tags[] = $Tag;
                }

                // backwards compatibility
                if ($command === 'api') {
                    $this->defaultTag = $Tag;
                }

                return $Tag;

            case 'endpoint':
                $path = self::wordShift($data);
                if (false === $path) {
                    $path = '/';
                } else if ($path[0] !== '/') {
                    $path = '/' . $path;
                }

                $Tag = null;
                if (($tagname = self::wordShift($data)) !== false) {
                    foreach ($this->tags as $T) {
                        if (strtolower($T->getName()) === strtolower($tagname)) {
                            $Tag = $T;
                            break;
                        }
                    }
                    if (!$Tag) {
                        $Tag = new Tag($this, $tagname, $data);
                        $this->tags[] = $Tag;
                    }
                }

                if (!isset($this->paths[$path])) {
                    $this->paths[$path] = new Path($this, $Tag ?: $this->defaultTag);
                }

                return $this->paths[$path];

            case 'security':
                $name = self::wordShift($data);
                if (empty($name)) {
                    throw new Exception('Missing security name');
                }
                $type = self::wordShift($data);
                if (empty($type)) {
                    throw new Exception('Missing security type');
                }
                $SecurityScheme = new SecurityScheme($this, $type, $data);
                $this->securityDefinitions[$name] = $SecurityScheme;

                return $SecurityScheme;

            case 'require':
                $name = self::wordShift($data);
                if (empty($name)) {
                    throw new Exception('Missing require name');
                }
                $scopes = self::wordSplit($data);
                sort($scopes);
                $this->security[] = [
                    $name => empty($scopes) ? [] : $scopes,
                ];

                return $this;

            default:
        }

        return parent::handleCommand($command, $data);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        if (empty($this->paths)) {
            throw new Exception('No path defined');
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
                    throw new Exception("Required security scheme not defined: '{$name}'");
                }
            }
        }

        return self::arrayFilterNull(array_merge([
            'swagger'             => $this->swagger,
            'info'                => $this->info->toArray(),
            'host'                => empty($this->host) ? null : $this->host,
            'basePath'            => empty($this->basePath) ? null : $this->basePath,
            'consumes'            => $consumes,
            'produces'            => $produces,
            'schemes'             => $schemes,
            'paths'               => self::objectsToArray($this->paths),
            'definitions'         => self::objectsToArray($this->definitions),
            'parameters'          => self::objectsToArray($this->parameters),
            'responses'           => self::objectsToArray($this->responses),
            'securityDefinitions' => self::objectsToArray($this->securityDefinitions),
            'security'            => $this->security,
            'tags'                => self::objectsToArray($this->tags),
        ], parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__;
    }

    /**
     * Check if an operation with the given id exists.
     *
     * @param string $operationId
     *
     * @return boolean
     */
    public function hasOperationId(string $operationId): bool
    {
        foreach ($this->paths as $path) {
            if ($path->hasOperationId($operationId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a definition with the given name exists
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasDefinition(string $name): bool
    {
        return isset($this->definitions[$name]);
    }

}
