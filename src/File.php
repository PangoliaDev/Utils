<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class File {

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function include( string $file ) {
		return include $file;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function includeOnce( string $file ) {
		return include_once $file;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function require( string $file ) {
		return require $file;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function requireOnce( string $file ) {
		return require_once $file;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function includeIfExists( string $file ) {
		return \file_exists( $file ) ? static::include( $file ) : false;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function includeOnceIfExists( string $file ) {
		return \file_exists( $file ) ? static::includeOnce( $file ) : false;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function requireIfExists( string $file ) {
		return \file_exists( $file ) ? static::require( $file ) : false;
	}

	/**
	 * @param string $file
	 * @return mixed
	 */
	public static function requireOnceIfExists( string $file ) {
		return \file_exists( $file ) ? static::requireOnce( $file ) : false;
	}
}
