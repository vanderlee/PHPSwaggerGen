<?php

class SwaggerEndpoint extends SwaggerAbstractBase {
	private $Methods = array();

	public $path;
	public $description;

	public function __construct(SwaggerAbstractApi $ApiBase, $path, $description = '') {
		parent::__construct($ApiBase);
		
		$this->path			= $path;
		$this->description	= $description;

		$ApiBase->addEndpoint($this);
	}

	public function addMethod(SwaggerMethod $Method) {
		$this->Methods[] = $Method;
	}

	public function toArray() {
		$array = array(
			'path'			=> $this->path,
			'description'	=> $this->description,
			'operations'	=> array(),
		);

		foreach ($this->Methods as $Method) {
			$array['operations'][] = $Method->toArray();
		}
		
		return array_filter($array);
	}
}
