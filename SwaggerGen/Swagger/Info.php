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

	private $title = 'undefined';
	private $description;
	private $termsofservice;

	/**
	 * @var Contact
	 */
	private $Contact;
	private $License;
	private $version = 0;

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
				foreach (self::words_split($data) as $word) {
					if (filter_var($word, FILTER_VALIDATE_URL)) {
						$url = $word;
					} elseif (filter_var($word, FILTER_VALIDATE_EMAIL)) {
						$email = $word;
					} else {
						$name[] = $word;
					}
				}
				$name = join(' ', array_filter($name));
				$this->Contact = new Contact($this, $name, $url, $email);
				return $this->Contact;

			case 'license':
				$name = array();
				$url = null;
				foreach (self::words_split($data) as $word) {
					if (filter_var($word, FILTER_VALIDATE_URL)) {
						$url = $word;
					} else {
						$name[] = $word;
					}
				}
				$name = join(' ', array_filter($name));
				$this->License = new License($this, $name, $url);
				return $this->License;
		}

		return parent::handleCommand($command, $data);
	}

	public function toArray()
	{
		return self::array_filter_null(array_merge([
					'title' => $this->title,
					'description' => $this->description,
					'termsOfService' => $this->termsofservice,
					'contact' => $this->Contact ? $this->Contact->toArray() : null,
					'license' => $this->License ? $this->License->toArray() : null,
					'version' => $this->version,
								], parent::toArray()));
	}

}
