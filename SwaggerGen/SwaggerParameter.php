<?php

class SwaggerParameter extends SwaggerAbstractPrimitive {
	const PARAMTYPE_PATH		= 'path';
	const PARAMTYPE_QUERY		= 'query';
	const PARAMTYPE_BODY		= 'body';
	const PARAMTYPE_HEADER		= 'header';
	const PARAMTYPE_FORM		= 'form';

	public $paramType;
	public $name;
	public $description;
	public $required;
	public $allowmultiple;

	public function __construct(SwaggerAbstractBase $Base, $paramType, $primitive, $name, $description = '') {
		parent::__construct($Base, $primitive);

		$this->paramType	= strtolower($paramType);
		$this->name			= $name;
		$this->description	= $description;

		$Base->AddParameter($this);
	}
	
	public function toArray() {
		$array = parent::toArray() + array(
			'name'			=> $this->name,
			'description'	=> $this->description,
			'paramType'		=> $this->paramType,
			'required'		=> $this->required,
			'allowMultiple'	=> $this->allowmultiple,
		);

		return array_filter($array, function($v) { return $v !== '' && $v !== null; } );
	}
}