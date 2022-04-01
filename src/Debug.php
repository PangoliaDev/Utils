<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Debug {

	/**
	 * Dump and die.
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
	 */
	public static function pd( $value ) {
		$html = "\n<pre>\n";
		$html .= \print_r( $value, true );
		$html .= "</pre>\n";
		echo $html;
		die;
	}

	/**
	 * Ray debugging
	 */
	public static function ray() {
		if ( class_exists('Spatie\WordPressRay\ray') ) {
			return \Spatie\WordPressRay\ray(...func_get_args());
		} else {
			return false;
		}
	}
}
