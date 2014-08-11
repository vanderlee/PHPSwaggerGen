<?php

/**
 * SwaggerPhpParseStrategy
 *
 * @author Martijn
 */
class SwaggerParseStrategyPhp implements ISwaggerParseStrategy {
	public static function parse($lines) {
		// Extract all (doc-)comment tokens
		$comments = array_filter(
			token_get_all(join("\n", $lines)), function($entry) {
				return $entry[0] == T_DOC_COMMENT || $entry[0] == T_COMMENT;
			}
		);

		// Split into arrays of comments
		array_walk($comments, function(&$comment) {
			$comment = preg_split('~\R~', $comment[1]);
			array_walk($comment, function(&$line) {
				$line = preg_replace('~^\s*/?\**/?\s*~', '', $line);
			});
			$comment = array_filter($comment);
		});

		// group commands
		$statements = array();
		foreach ($comments as $comment) {
			$statement = array();

			foreach ($comment as $line) {
				if (strpos($line, '@rest\\') !== false) {
					$line = substr($line, 6);
					if (!empty($statement)) {
						$statements[] = join(' ', $statement);
						$statement = array();
					}
				}
				$statement[] = $line;
			}
			$statements[] = join(' ', $statement);
		}

		return $statements;
	}
}
