<?php

namespace Api\Rest;

/*
 * @rest\description SwaggerGen 2 Example API
 * @rest\title Example API
 * @rest\contact http://example.com Arthur D. Author
 * @rest\license MIT
 * @rest\security api_key apikey X-Api-Authentication header Authenticate using this fancy header
 * @rest\require api_key
 */

/**
 * @rest\error 404 This is not the resource you are looking for
 * @rest\x-http-message 404
 * @rest\doc http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
 * RFC2616, sec.10: W3C status code definitions
 */
function NotFound()
{
	header("HTTP/1.0 404 Not Found");
}

/**
 * This class demonstrates how to use the SwaggerGen PHPDoc comments.
 * It is not meant as an example of a solid Rest API or a complete demonstration
 * of all SwaggerGen features.
 *
 * @rest\api Users Get some useful information on users
 */
class Example
{

	private $data = array();

	public function __construct()
	{
		$this->data = json_decode(file_get_contents('example.json'), true);
	}

	public function request($method, $path, $data = null)
	{
		preg_match_all('/([^\/]+)(?:\/([^\/]+))?/', $path, $matches, PREG_SET_ORDER);

		$arguments = array();
		$methodname = strtolower($method);
		foreach ($matches as $match) {
			$methodname .= ucfirst($match[1]);
			if (empty($match[2])) {
				$methodname .= '_data';
			} else {
				$arguments[] = $match[2];
			}
		}

		if (!method_exists($this, $methodname)) {
			throw new \Exception('Method Not Allowed', 405); // @rest\errors 405
		}

		$arguments[] = $data;
		return call_user_func_array(array($this, $methodname), $arguments);
	}

	/**
	 * @rest\endpoint /user
	 * @rest\method GET Get a list of all users
	 * @rest\require api_key
	 * @rest\response 200 array(string)
	 */
	private function getUser_data()
	{
		return array_keys($this->data['users']);
	}

	/**
	 * @rest\ifdef admin
	 * @rest\endpoint /user/{username}
	 * @rest\method DELETE Delete a specific user
	 * @rest\endif
	 */
	private function deleteUser($name)
	{
		// Pretend we're deleting the user.
		return array();
	}

	/**
	 * @rest\ifdef root
	 * @rest\endpoint /user
	 * @rest\method DELETE Delete all users
	 * @rest\endif
	 */
	private function deleteAllUsers($name)
	{
		// Pretend we're deleting all users.
		return array();
	}

	/**
	 * @rest\endpoint /user/{username}
	 * @rest\method GET Get a list of all users
	 * @rest\path String username Name of the user
	 * @rest\see self::request
	 * @todo get "errors" from self::request, without context issues; filter out methods without a @rest\method statement
	 */
	private function getUser($name)
	{
		/**
		 * @rest\model User
		 * @rest\property int age Age of the user in years
		 * @rest\example 123
		 * @rest\property int height Height of the user in centimeters
		 */
		//return $this->data['users'][$name]; // @rest\response OK User
		return $this->data['users'][$name]; // @rest\response OK object(age:int[0,100>,height:float) User
	}

	private function getUserAge_data($name)
	{
		return $this->data['users'][$name]['age'];
	}

	private function getUserHeight_data($name)
	{
		return $this->data['users'][$name]['height'];
	}

	private function getUserHeight($name, $unit)
	{
		static $units = array(
			'meter' => 100,
			'inch' => 2.54,
			'foot' => 30.48,
			'yard' => 91.44,
		);

		return $this->data['users'][$name]['height'] / $units[$unit];
	}

	private function getAge_data()
	{
		$age = 0;

		foreach ($this->data['users'] as $user) {
			$age += $user['age'];
		}

		return $age / count($this->data['users']);
	}

	/**
	 * @rest\endpoint /user/{username}/image
	 * @rest\method put
	 * @rest\consumes fileform
	 * @rest\path String username Name of the user
	 * @rest\form file File Image file (PNG, GIF or JPEG) for the user.
	 * Any transparency in the file is replaced with a white background color.
	 * Files will be cropped to a square image.
	 */
	private function putUserImage($name, $data)
	{
		// not implemented
		// @rest\see NotFound
	}

}
