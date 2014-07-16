<?php

abstract class SwaggerAbstractPrimitive extends SwaggerAbstractScope {
	public $type;
	public $default;
	public $enum;
	public $minimum;
	public $maximum;	
	public $items;
	public $uniqueitems;

	private static $primitives = array(
		'integer' => array(
			'type' => 'integer',
			'format' => 'int32',
			'items' => 'integer',
		),
		'long' => array(
			'type' => 'integer',
			'format' => 'int64',
			'items' => 'integer',
		),
		'float' => array(
			'type' => 'number',
			'format' => 'float',
			'items' => 'number',
		),
		'double' => array(
			'type' => 'number',
			'format' => 'double',
			'items' => 'number',
		),
		'string' => array(
			'type' => 'string',
			'format' => '',
			'items' => 'string',
		),
		'byte' => array(
			'type' => 'string',
			'format' => 'byte',
			'items' => 'string',
		),
		'boolean' => array(
			'type' => 'boolean',
			'format' => '',
			'items' => 'boolean',
		),
		'date' => array(
			'type' => 'string',
			'format' => 'date',
			'items' => 'string',
		),
		'datetime' => array(
			'type' => 'string',
			'format' => 'date-time',
			'items' => 'string',
		),
		'array' => array(
			'type' => 'array',
			'format' => '',
			'items' =>
			NULL,
		),
		'set' => array(
			'type' => 'array',
			'format' => 'set',
			'items' =>
			NULL,
		),
		'file' => array(
			'type' => 'File',
			'format' =>
			NULL,
			'items' =>
			NULL,
		),
		'void' => array(
			'type' => 'void',
			'format' =>
			NULL,
			'items' =>
			NULL,
		),
	);

	public function __construct(SwaggerAbstractBase $Base, $primitive) {
		parent::__construct($Base);

		$this->setPrimitive($primitive);
	}
	
	private function setPrimitive($primitive) {
		if (preg_match('~^(\w+)\\(([^,)]*),?([^)]*)\\)$~', $primitive, $match) === 1) {
			$this->type		= $match[1];
			
			if (in_array($match[1], array('array', 'set'))) {
				$this->items = $match[2];				
				if ($match[1] === 'set') {
					$this->uniqueitems = true;
				}
			} else if (isset($match[2])) {
				$this->minimum = $match[2];
				$this->maximum = $match[empty($match[3])? 2 : 3];
			}
		} else {		
			$this->type		= $primitive;
		}		
	}

	private function getType() {
		if (isset(self::$primitives[strtolower($this->type)]['type'])) {
			return self::$primitives[strtolower($this->type)]['type'];
		}
		return null;
	}

	private function getFormat() {
		if (isset(self::$primitives[strtolower($this->type)]['format'])) {
			return self::$primitives[strtolower($this->type)]['format'];
		}
		return null;
	}
	
	private function getItems() {
		if (isset(self::$primitives[strtolower($this->items)]['items'])) {
			return self::$primitives[strtolower($this->items)]['items'];
		}
		return null;
	}

	public function toArray() {
		$type = $this->getType();

		$array = array(
			'type'			=> $type ?: $this->type,
			'$ref'			=> $type ? '' : $this->type,
			'format'		=> $this->getFormat(),
			'defaultValue'	=> $this->default,
			'enum'			=> $this->enum,
			'minimum'		=> $this->minimum,
			'maximum'		=> $this->maximum,
			'items'			=> $this->getItems() ?: $this->items,	
			'uniqueItems'	=> $this->uniqueitems,
		);

		return array_filter($array, function($v) { return $v !== '' && $v !== null; } );
	}
}