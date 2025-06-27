<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Contact object, containing contact information for the
 * documented API.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Contact extends AbstractObject
{

    private $name;
    private $url;
    private $email;

    public function __construct(AbstractObject $parent, $name = null, $url = null, $email = null)
    {
        parent::__construct($parent);

        $this->name = empty($name) ? null : $name;
        $this->url = empty($url) ? null : $url;
        $this->email = empty($email) ? null : $email;
    }

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'name':
            case 'url':
            case 'email':
                $this->$command = $data;
                return $this;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray(): array
    {
        return self::arrayFilterNull(array_merge(array(
            'name' => $this->name,
            'url' => $this->url,
            'email' => $this->email,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__ . " {$this->name} <{$this->email}>, {$this->url}";
    }

}
