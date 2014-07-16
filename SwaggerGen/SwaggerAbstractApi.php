<?php

abstract class SwaggerAbstractApi extends SwaggerAbstractBase {
	public $Models			= array();
	public $basepath;
	public $resourcepath;
	public $Endpoints		= array();

	public function addModel(SwaggerModel $Model) {
		$this->Models[] = $Model;
	}

	public function addEndpoint(SwaggerEndpoint $Endpoint) {
		$this->Endpoints[] = $Endpoint;
	}

	public function getEndpoint($path) {
		foreach ($this->Endpoints as $Endpoint) {
			if ($Endpoint->path === $path) {
				return $Endpoint;
			}
		}
		
		return null;
	}
}