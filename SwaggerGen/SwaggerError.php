<?php

class SwaggerError extends SwaggerAbstractScope {
	private $code;
	private $message;
	public $Model;	//@todo

	private static $messages = array(
		400	=> "Bad Request",				// BR
		401	=> "Unauthorized",				// U
		403	=> "Forbidden",					// F
		404	=> "Resource Not Found",		// RNF
		405	=> "Method Not Allowed",		// MNA
		410	=> "Gone",						// G
		415	=> "Unsupported Media Type",	// UMT
		422	=> "Unprocessable Entity",		// UE
		429	=> "Too Many Requests",			// TMR
		500	=> "Internal Server Error",		// ISE
		501	=> "Not Implemented",			// NI
	);	

	public function __construct(SwaggerAbstractBase $Base, $code, $message = '') {
		parent::__construct($Base);

		$this->code			= $code;
		$this->message		= $message;
		
		$Base->addError($this);
	}
	
	private function getMessage() {
		if (empty($this->message) && isset(self::$messages[$this->code])) {
			return self::$messages[$this->code];
		}
		return $this->message;
	}

	public function toArray() {
		return array(
			'code'			=> $this->code,
			'message'		=> $this->getMessage(),
		);
	}
}