<?php

/**
 * SwaggerGenericParseStrategy
 *
 * @author Martijn
 */
class SwaggerParseStrategyText implements ISwaggerParseStrategy {
	public static function parse($lines) {
		return $lines;
	}
}
