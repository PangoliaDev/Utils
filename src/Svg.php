<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Svg {
	/**
	 * SVG lazy attribute
	 *
	 * @var string
	 */
	protected static string $lazy_attr = 'data-lazy';

	/**
	 * SVG elements
	 *
	 * @var array<string, string|string[]|array>
	 */
	protected static array $svg_inline;

	/**
	 * SVG sprites
	 *
	 * @var array<string, string>
	 */
	protected static array $svg_sprites;

	/**
	 * @param array<string, string|string[]|array> $config
	 * @return void
	 */
	public static function config( array $config ) {
		static::$svg_inline = $config['inline'] ?? [];
		static::$svg_sprites = $config['sprites'] ?? [];
	}

	/**
	 * Render and inject an SVG from a file
	 *
	 * @param string                $file_path
	 * @param array<string, string> $attributes
	 * @param array<string, string> $colors
	 * @return string
	 */
	public static function inject_file( string $file_path, array $attributes = [], array $colors = [] ): string {
		return static::create_inline_svg( \file_get_contents( $file_path ), $attributes, $colors );
	}

	/**
	 * Render inline SVG
	 *
	 * @param string                $svg
	 * @param string                $group
	 * @param array<string, string> $attributes
	 * @param array<string, string> $colors
	 * @return string
	 */
	public static function render_inline( string $svg, string $group = 'ui', array $attributes = [], array $colors = [] ): string {
		return static::create_inline_svg( static::$svg_inline[ $group ][ $svg ], $attributes, $colors );
	}

	/**
	 * @param string                $sprite
	 * @param string                $id
	 * @param array<string, string> $attributes
	 * @param bool                  $lazy
	 * @return string
	 */
	public static function render_sprite( string $sprite, string $id, array $attributes = [], bool $lazy = false ): string {
		if ( $lazy === false || static::disable_lazy_conditions() ) {
			$xlink_href = static::$svg_sprites[ $sprite ] . "#{$id}";
		} else {
			$attributes[ static::$lazy_attr ] = static::$svg_sprites[ $sprite ] . "#{$id}";
			$xlink_href = 'http://www.w3.org/1999/xlink';
		}

		$svg_attr = static::create_attr( $attributes );

		$svg_string = "<svg {$svg_attr}>";
		$svg_string .= "<use xlink:href='{$xlink_href}'/>";
		$svg_string .= "</svg>";

		return $svg_string;
	}

	/**
	 * Modify SVG html code
	 *
	 * @param string                $svg_element
	 * @param array<string, string> $attributes
	 * @param array<string, string> $colors
	 * @return string
	 */
	protected static function create_inline_svg( string $svg_element, array $attributes = [], array $colors = [] ): string {
		$search = [ '<svg ' ];
		$replace = [ '<svg ' . static::create_attr( $attributes ) ];

		if ( ! empty( $colors ) ) {
			foreach ( $colors as $search_color => $replace_color ) {
				$search[] = $search_color;
				$replace[] = $replace_color;
			}
		}

		return \str_replace( $search, $replace, $svg_element );
	}

	/**
	 * Create html attributes
	 *
	 * @param array<string, string> $attributes
	 * @param string                $svg_attr
	 * @return string
	 */
	protected static function create_attr( array $attributes, string $svg_attr = '' ): string {
		foreach ( $attributes as $attr => $spec ) {
			$svg_attr .= "{$attr}='{$spec}' ";
		}
		return $svg_attr;
	}

	/**
	 * Conditions to disable lazy loading SVGs from sprites.
	 *
	 * @return bool
	 */
	protected static function disable_lazy_conditions(): bool {
		return strpos( $_SERVER['REQUEST_URI'], 'action=elementor' ) !== false;
	}
}
