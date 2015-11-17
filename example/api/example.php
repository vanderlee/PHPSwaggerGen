<?php

	require_once 'Example.class.php';

	$method = $_SERVER['REQUEST_METHOD'];
	$path	= $_GET['endpoint'];
	$data	= $_POST;

	$Example = new Api\Rest\Example();

	try {
		$result = $Example->request($method, $path, $data);

		header('Content-Type: application/json');
		echo json_encode($result, JSON_NUMERIC_CHECK);
	} catch (Exception $e) {
		header('HTTP/1.0 ' . $e->getCode() . ' ' . $e->getMessage());
	}
