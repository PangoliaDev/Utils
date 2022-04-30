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
	 */
	protected static array $template_args;

	/**
	 * Custom templates
	 */
	protected static array $custom_templates;

	/**
	 * @param array<string, string|string[]|array> $config
	 * @return void
	 * @throws \Exception
	 */
	public static function config( array $config ) {
		static::$template_path = $config['path'] ?? [];
		static::$template_args = $config['args'] ?? [];
		static::$custom_templates = $config['custom'] ?? [];

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
			\apply_filters( 'filter_template_args',
				\array_merge( $args, static::$template_args ),
				$path,
				$template
			)
		);

		if ( $template === false ) {
			return false;
		}
	}

	/**
	 * @param string                   $path     Component source path.
	 * @param array<int|string, mixed> $args     Optional. Additional arguments passed to the template.
	 *                                           Default empty array.
	 * @return false|void
	 */
	public static function get_component( string $path, array $args = [] ) {
		return static::get( "Components/{$path}", \sanitize_title($path), $args );
	}

	/**
	 * Determines whether currently in this template.
	 *
	 * @param string|array $template The specific template filename or array of templates to match.
	 * @return bool True on success, false on failure.
	 */
	public static function is( $template ): bool {
		if ( isset( static::$custom_templates[ $template ] ) ) {
			return \is_page_template( static::$custom_templates[ $template ]['file'] );
		} else {
			return \is_page_template( $template );
		}
	}

	/**
	 * Check if the specific template filename for a given post matches our template
	 *
	 * @param string|array $template The specific template filename or array of templates to match.
	 * @return bool True on success, false on failure.
	 */
	public static function is_slug( $template, $post = null ): bool {
		if ( isset( static::$custom_templates[ $template ] ) ) {
			return \get_page_template_slug( $post ) === static::$custom_templates[ $template ]['file'];
		} else {
			return \get_page_template_slug( $post ) === $template;
		}
	}
}
