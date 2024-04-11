<?php

namespace SwaggerGen\Swagger;

/**
 * Describes a Swagger Info object, containing non-technical details about the
 * documented API.
 *
 * @package    SwaggerGen
 * @author     Martijn van der Lee <martijn@vanderlee.com>
 * @copyright  2014-2015 Martijn van der Lee
 * @license    https://opensource.org/licenses/MIT MIT
 */
class Info extends AbstractObject
{

    /**
     * @var string
     */
    private $title = 'undefined';

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $termsofservice;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * @var License
     */
    private $license;

    /**
     * @var string|integer|float
     */
    private $version = 0;

    /**
     * @param string $command
     * @param string $data
     * @return AbstractObject|boolean
     */
    public function handleCommand($command, $data = null)
    {
        switch (strtolower($command)) {
            case 'title':
            case 'description':
            case 'termsofservice':
            case 'version':
                $this->$command = $data;
                return $this;

            case 'terms': // alias
            case 'tos': // alias
                $this->termsofservice = $data;
                return $this;

            case 'contact':
                $name = array();
                $url = null;
                $email = null;
                foreach (self::wordSplit($data) as $word) {
                    if (filter_var($word, FILTER_VALIDATE_URL)) {
                        $url = $word;
                    } elseif (filter_var($word, FILTER_VALIDATE_EMAIL)) {
                        $email = $word;
                    } else {
                        $name[] = $word;
                    }
                }
                $name = join(' ', array_filter($name));
                $this->contact = new Contact($this, $name, $url, $email);
                return $this->contact;

            case 'license':
                $name = array();
                $url = null;
                foreach (self::wordSplit($data) as $word) {
                    if (filter_var($word, FILTER_VALIDATE_URL)) {
                        $url = $word;
                    } else {
                        $name[] = $word;
                    }
                }
                $name = join(' ', array_filter($name));
                $this->license = new License($this, $name, $url);
                return $this->license;
        }

        return parent::handleCommand($command, $data);
    }

    public function toArray()
    {
        return self::arrayFilterNull(array_merge(array(
            'title' => $this->title,
            'description' => $this->description,
            'termsOfService' => $this->termsofservice,
            'contact' => $this->contact ? $this->contact->toArray() : null,
            'license' => $this->license ? $this->license->toArray() : null,
            'version' => (string)$this->version,
        ), parent::toArray()));
    }

    public function __toString()
    {
        return __CLASS__ . ' \'' . $this->title . '\'';
    }

}
