<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

/**
 * Collection of template helpers
 *
 * @since 0.1.0
 */
class Templates {

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
			"src/{$path}/Static/templates/{$template}",
			null,
			$args
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

