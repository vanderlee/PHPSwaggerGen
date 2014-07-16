<?php

class SwaggerApi extends SwaggerAbstractApi {
	public $name;
	public $description;

	public function __construct(SwaggerResource $Resource, $name, $description = '') {
		$this->parent		= $Resource;
		$this->name			= $name;
		$this->description	= $description;

		$Resource->addApi($this);
	}

	public function toArray() {
		$array = array(
			'apiVersion'		=> $this->getByProperty('apiversion')->apiversion,
			'swaggerVersion'	=> $this->getByProperty('swaggerversion')->swaggerversion,
			'basePath'			=> $this->basepath ?: $this->parent->basepath,
			'resourcePath'		=> $this->resourcepath ?: $this->parent->resourcepath,
			'produces'			=> $this->getProperties('produces'),
			'consumes'			=> $this->getProperties('consumes'),
			'apis'				=> $this->getProperties('Endpoints'),
			'models'			=> array(),
		);

		foreach ($this->getProperties('Models') as $model) {
			$array['models'][$model['id']] = $model;
		}
		
		return array_filter($array);
	}
}