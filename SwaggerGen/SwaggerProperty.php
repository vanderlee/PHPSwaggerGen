<?php

class SwaggerProperty extends SwaggerAbstractPrimitive {
	public $name;
	public $description;

	public function __construct(SwaggerModel $Model, $primitive, $name, $description = '') {
		parent::__construct($Model, $primitive);

		$this->name			= $name;
		$this->description	= $description;
		
		$Model->AddProperty($this);
	}

	public function toArray() {
		$array = parent::toArray() + array(
			'name'			=> $this->name,
			'description'	=> $this->description,
		);

		return array_filter($array, function($v) { return $v !== '' && $v !== null; } );
	}
}