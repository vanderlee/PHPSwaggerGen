<?php

abstract class SwaggerAbstractBase extends SwaggerAbstractScope {
	/**
	 * @var SwaggerAbstractBase
	 */
	protected $Parameters		= array();
	public $produces		= array();
	public $consumes		= array();
	protected $Errors			= array();

	public function addError(SwaggerError $Error) {
		$this->Errors[] = $Error;
	}

	public function addParameter(SwaggerParameter $Parameter) {
		$this->Parameters[] = $Parameter;
	}
}