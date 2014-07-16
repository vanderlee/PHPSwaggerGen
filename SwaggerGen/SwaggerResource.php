<?php

class SwaggerResource extends SwaggerAbstractApi {
	public $apiversion			= '1.0.0';
	public $swaggerversion		= '1.2';
	public $title				= 'RESTful API';
	public $description			= 'About this API.';
	public $termsofserviceurl;
	public $contact;
	public $license;
	public $licenseurl;
	
	public $Apis = array();

	public function addApi(SwaggerApi $Api) {
		$this->Apis[] = $Api;
	}
	
	// @todo make this a separate resource file? (licenseUrls.json)
	private static $licenseUrls = array(
		'mit'			=> 'http://opensource.org/licenses/MIT',
		'apache 2.0'	=> 'http://www.apache.org/licenses/LICENSE-2.0.html',
	);

	public function getApi($name) {
		foreach ($this->Apis as $Api) {
			if ($Api->name === $name) {
				return $Api;
			}
		}

		return null;
	}
	
	private function getLicenseUrl() {
		if ($this->licenseurl) {
			return $this->licenseurl;
		}
		
		if (isset(self::$licenseUrls[strtolower($this->license)])) {
			return self::$licenseUrls[strtolower($this->license)];
		}
		
		return '';
	}

	public function toArray() {
		$array = array(
			'apiVersion'		=> $this->apiversion,
			'swaggerVersion'	=> $this->swaggerversion,
			'apis'	=> array(),
			'info'	=> array_filter(array(
				'title'				=> $this->title,
				'description'		=> $this->description,
				'termsOfServiceUrl'	=> $this->termsofserviceurl,
				'contact'			=> $this->contact,
				'license'			=> $this->license,
				'licenseUrl'		=> $this->getLicenseUrl(),
			)),
		);

		foreach ($this->Apis as $Api) {
			$array['apis'][] = array(
				'path'			=> '/' . $Api->name,
				'description'	=> $Api->description,
			);
		}

		return $array;
	}
}