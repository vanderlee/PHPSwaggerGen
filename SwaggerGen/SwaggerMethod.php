<?php

class SwaggerMethod extends SwaggerAbstractBase {
	public $method;
	public $summary;
	public $notes;
	public $deprecated;

	public function __construct(SwaggerEndpoint $Endpoint, $method, $summary) {
		parent::__construct($Endpoint);

		$this->method	= $method;
		$this->summary	= $summary;

		$Endpoint->addMethod($this);
	}

	private function getNickname() {
		return lcfirst(preg_replace('~[^[:alpha:]]~', '', ucwords($this->summary)));
	}

	public function toArray() {
		$errors = $this->getProperties('Errors');
		$errors = array_unique($errors, SORT_REGULAR);
		sort($errors);

		$array = array(
			'method'			=> strtoupper($this->method),
			'summary'			=> $this->summary,
			'notes'				=> $this->notes,
			'nickname'			=> $this->getNickname(),
			'parameters'		=> $this->getProperties('Parameters'),
			'responseMessages'	=> $errors,
			'deprecated'		=> $this->deprecated,
		);

		return array_filter($array);
	}
}