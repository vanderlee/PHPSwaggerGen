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
		'integer' => array('type' => 'integer','format' => 'int32','items' => true),
		'long' => array('type' => 'integer','format' => 'int64','items' => true),
		'float' => array('type' => 'number','format' => 'float','items' => true),
		'double' => array('type' => 'number','format' => 'double','items' => true),
		'string' => array('type' => 'string','format' => '','items' => true),
		'byte' => array('type' => 'string','format' => 'byte','items' => true),
		'boolean' => array('type' => 'boolean','format' => '','items' => true),
		'date' => array('type' => 'string','format' => 'date','items' => true),
		'datetime' => array('type' => 'string','format' => 'date-time','items' => true),
		'array' => array('type' => 'array',	'format' => '',	'items' =>	false),
		'set' => array('type' => 'array', 'format' => 'set', 'items' =>	false),
		'file' => array('type' => 'File', 'format' => null, 'items' => false),
		'void' => array('type' => 'void', 'format' => null, 'items' => false),

		// Non-standard convenience types
		'enum' => array('type' => 'string', 'format' => '', 'items' => false),
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
			} else if ($match[1] === 'enum') {
				$this->enum = explode(',', $match[2].','.$match[3]);
			} else if (isset($match[2])) {
				$this->minimum = $match[2];
				$this->maximum = $match[empty($match[3])? 2 : 3];
			}
		} else {
			$this->type		= $primitive;
		}
	}

	private function getType($type) {
		if (isset(self::$primitives[strtolower($type)]['type'])) {
			return self::$primitives[strtolower($type)]['type'];
		}
		return null;
	}

	private function getFormat($type) {
		if (isset(self::$primitives[strtolower($type)]['format'])) {
			return self::$primitives[strtolower($type)]['format'];
		}
		return null;
	}

	private function isPrimitiveItems($items) {
		if (isset(self::$primitives[strtolower($items)]['items'])) {
			return self::$primitives[strtolower($items)]['items'];
		}
		
		return false;
	}

	public function toArray() {
		$type = $this->getType($this->type);
		
		$isPrimitive = $this->isPrimitiveItems($this->items);
		$items = array(
			'type'			=> $isPrimitive ? $this->getType($this->items) : null,
			'$ref'			=> $isPrimitive ? null : $this->items,
			'format'		=> $isPrimitive ? $this->getFormat($this->items) : null,
		);
		$items = array_filter($items, function($v) { return $v !== '' && $v !== null; } );

		$array = array(
			'type'			=> $type ?: $this->type,
			'$ref'			=> $type ? null : $this->type,
			'format'		=> $this->getFormat($this->type),
			'defaultValue'	=> $this->default,
			'enum'			=> $this->enum,
			'minimum'		=> $this->minimum,
			'maximum'		=> $this->maximum,
			'items'			=> $this->items ? $items : null,
			'uniqueItems'	=> $this->uniqueitems,
		);

		return array_filter($array, function($v) { return $v !== '' && $v !== null; } );
	}
}