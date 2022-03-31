<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Debug {

	/**
	 * Dump and die.
	 *
	 * @param mixed $value The variable you want to export.
	 * @return void
	 *
	 * @since 0.1.0
	 */
	public static function dd( $value ) {
		\ob_end_clean();
		$backtrace = \debug_backtrace();
		$html = "\n<pre>\n";
		if ( isset( $backtrace[0]['file'] ) ) {
			$filename = explode( '\\', $backtrace[0]['file'] );
			$html .= end( $filename ) . "\n\n";
		}
		$html .= "---------------------------------\n\n";
		\var_dump( $value );
		$html .= "</pre>\n";
		echo $html;
		die;
	}

	/**
	 * Print and die.
	 *
	 * @param mixed $value The expression to be printed.
	 * @return void
	 * @since 0.1.0
	 */
	public static function pd( $value ) {
		$html = "\n<pre>\n";
		$html .= \print_r( $value, true );
		$html .= "</pre>\n";
		echo $html;
		die;
	}
}
