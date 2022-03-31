<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Templates {

	/**
	 * Path to templates
	 *
	 * @var string|array
	 */
	protected static $template_path;

	/**
	 * Template global arguments
	 *
	 * @var array
	 */
	protected static array $template_args;

	/**
	 * @param array<string, string|string[]|array> $config
	 * @return void
	 * @throws \Exception
	 */
	public static function config( array $config ) {
		static::$template_path = $config['path'] ?? [];
		static::$template_args = $config['args'] ?? [];

		if ( ! is_callable( static::$template_path ) ) {
			throw new \Exception( 'Template path is not callable' );
		}
	}

	/**
	 * Wrapper for get template function
	 *
	 * @param string                   $path     Project source path.
	 * @param string                   $template The slug name for the generic template.
	 * @param array<int|string, mixed> $args     Optional. Additional arguments passed to the template.
	 *                                           Default empty array.
	 * @return void|false Void on success, false if the template does not exist.
	 */
	public static function get( string $path, string $template, array $args = [] ) {
		$template = \get_template_part(
			\call_user_func(
				static::$template_path,
				$path,
				$template
			),
			null,
			\array_merge( $args, static::$template_args )
		);

		if ( $template === false ) {
			return false;
		}
	}

	/**
	 * Get template from the components folder
	 *
	 * @param string                   $path     Project source path.
	 * @param string                   $template The slug name for the generic template.
	 * @param array<int|string, mixed> $args     Optional. Additional arguments passed to the template.
	 *                                           Default empty array.
	 * @return void|false Void on success, false if the template does not exist.
	 */
	public static function get_component( string $path, string $template, array $args = [] ) {
		$template = static::get( $path, "components/{$template}", $args );

		if ( $template === false ) {
			return false;
		}
	}
}

