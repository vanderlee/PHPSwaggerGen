<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SwaggerGen test</title>
    </head>
    <body>
		<h1>SwaggerGen test</h1>
		<?php

			require 'SwaggerGen/autoloader.php';

			$SwaggerGen	= new SwaggerGen(array(
				'test-source/test.txt',
			), 'http://localhost/api');

			$arrays = $SwaggerGen->process();

			foreach ($arrays as $name => $array) {
				$filename = "test-swagger/{$name}.json";
				$json = json_encode($array);
				file_put_contents($filename, $json);

				echo "<div>Generated '$name' to file '{$filename}'</div>";
			}
			
		?>
		<hr/>
		<a href="test-ui/">View in Swagger-UI</a>
    </body>
</html>