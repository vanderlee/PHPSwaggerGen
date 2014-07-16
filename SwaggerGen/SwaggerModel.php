<?php

class SwaggerModel extends SwaggerAbstractBase {
	public $name;
	public $description;
	public $required;
	public $Properties = array();
	public $subtypes;				//@todo support
	public $discriminator;			//@todo support

	public function __construct(SwaggerAbstractApi $ApiBase, $name, $description = '') {
		parent::__construct($ApiBase);

		$this->name			= $name;
		$this->description	= $description;

		$ApiBase->addModel($this);
	}

	public function addProperty(SwaggerProperty $Property) {
		$this->Properties[] = $Property;
	}

	public function toArray() {
		$array = array(
			'id'			=> $this->name,
			'description'	=> $this->description,
			'required'		=> $this->required,
			'properties'	=> array(),
			'subTypes'		=> $this->subtypes,
			'discriminator'	=> $this->discriminator,
		);

		foreach ($this->getProperties('Properties') as $property) {
			$name = $property['name'];
			unset($property['name']);
			$array['properties'][$name] = $property;
		}

		return array_filter($array, function($v) { return $v !== '' && $v !== null; } );
	}
}