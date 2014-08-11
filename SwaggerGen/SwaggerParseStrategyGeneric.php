<?php

/**
 * SwaggerGenericParseStrategy
 *
 * @author Martijn
 */
class SwaggerParseStrategyGeneric implements ISwaggerParseStrategy {
	public static function parse($lines) {
		$statements = array();
		foreach ($lines as $line) {
			$matches = null;
			if (preg_match('~^.+@rest\\\(.*)$~', $line, $matches) === 1) {
				$statements[] = $matches[1];
			}
		}

		return $statements;
	}
}
