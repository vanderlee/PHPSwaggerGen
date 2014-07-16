<?php

abstract class SwaggerAbstractScope {
	public $parent = null;
	
	public function __construct(SwaggerAbstractScope $parent = null) {
		$this->parent = $parent;
	}
	
	abstract public function toArray();

	protected function getProperties($propertyName) {
		$properties = array();

		if ($this->parent instanceof self) {
			foreach ($this->parent->getProperties($propertyName) as $property) {
				$properties[] = $property;
			}
		}

		if (property_exists($this, $propertyName)) {
			foreach ($this->{$propertyName} as $property) {
				if ($property instanceof self) {
					$property = $property->toArray();
				}
				$properties[] = $property;
			}
		}
		
		return $properties;
	}
	
	/**
	 * 
	 * @param type $class_name
	 * @return \SwaggerAbstractScope|null
	 */
	public function getByClass($class_name) {
		if (is_a($this, $class_name)) {
			return $this;
		}
		
		if ($this->parent) {
			return $this->parent->getByClass($class_name);
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param type $property_name
	 * @return \SwaggerAbstractScope|null
	 */
	public function getByProperty($property_name) {
		if (property_exists($this, $property_name)) {
			return $this;			
		}
		
		if ($this->parent) {
			return $this->parent->getByProperty($property_name);
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param type $method_name
	 * @return \SwaggerAbstractScope|null
	 */
	public function getByMethod($method_name) {
		if (method_exists($this, $method_name)) {
			return $this;			
		}
		
		if ($this->parent) {
			return $this->parent->getByMethod($method_name);
		}
		
		return null;
	}
}
