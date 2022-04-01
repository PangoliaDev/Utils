<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Str {

	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param string          $haystack
	 * @param string|string[] $needles
	 * @return bool
	 * @since 0.1.0
	 */
	public static function starts_with( string $haystack, $needles ): bool {
		foreach ( (array) $needles as $needle ) {
			if ( (string) $needle !== '' && strncmp( $haystack, $needle, strlen( $needle ) ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param string          $haystack
	 * @param string|string[] $needles
	 * @return bool
	 * @since 0.1.0
	 */
	public static function ends_with( string $haystack, $needles ): bool {
		foreach ( (array) $needles as $needle ) {
			if ( $needle !== '' && substr( $haystack, -strlen( $needle ) ) === (string) $needle ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param string          $haystack
	 * @param string|string[] $needles
	 * @return bool
	 * @since 0.1.0
	 */
	public static function contains( string $haystack, $needles ): bool {
		foreach ( (array) $needles as $needle ) {
			if ( $needle !== '' && strpos( $haystack, $needle ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $needle
	 * @return bool
	 */
	public static function request_url_contains( string $needle ): bool {
		$current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		return strpos( $current_url, $needle ) !== false;
	}

	/**
	 * Checks if the string is a URL.
	 *
	 * @param string $string The string to validate.
	 * @return bool
	 * @since 0.1.0
	 */
	public static function is_url( string $string ): bool {
		return \filter_var( $string, \FILTER_VALIDATE_URL ) !== false;
	}

	/**
	 * Cleans the string and strips away all the tags and shortcodes
	 *
	 * @param string $string    Text to clean.
	 * @param bool   $trim      Whether to trim the cleaned string.
	 * @param int    $num_words Number of words if $string needs to be trimmed. Default 55.
	 * @param string $more      Optional. What to append if $string needs to be trimmed. Default '...'.
	 * @return string
	 */
	public static function clean( string $string, bool $trim = false, int $num_words = 55, string $more = '...' ): string {
		$string = wp_strip_all_tags( strip_shortcodes( $string ) );
		return $trim === true
			? wp_trim_words( $string, $num_words, $more )
			: $string;
	}

	/**
	 * Scan and extract all images from a given string.
	 *
	 * @param string $string
	 * @return mixed
	 */
	public static function extract_images( string $string ) {
		preg_match_all( '/<img[\s\r\n]+.*?>/is', $string, $matches );
		return $matches[0];
	}

	/**
	 * Scan and extract the first image from a given string.
	 *
	 * @param string $string
	 * @return mixed
	 */
	public static function extract_image( string $string ) {
		preg_match( '/<img[\s\r\n]+.*?>/is', $string, $match );
		return array_pop( $match );
	}

	/**
	 * Scan and extract the "src=" value from an image html string.
	 *
	 * @param string $string
	 * @return mixed|null
	 */
	public static function extract_image_src( string $string ) {
		preg_match( '@src="([^"]+)"@', $string, $match );
		return array_pop( $match );
	}

	/**
	 * Scan and extract the "width=" value from an html string.
	 *
	 * @param string $string
	 * @return mixed|null
	 */
	public static function extract_width( string $string ) {
		preg_match( '@width="([^"]+)"@', $string, $match );
		return array_pop( $match );
	}

	/**
	 * Scan and extract the "width=" value from an html string.
	 *
	 * @param string $string
	 * @return mixed|null
	 */
	public static function extract_height( string $string ) {
		preg_match( '@height="([^"]+)"@', $string, $match );
		return array_pop( $match );
	}

	/**
	 * Adds a class to a html tag.
	 *
	 * @param string $html  The HTML "tag" code we want to modify
	 * @param string $tag   The "tag" the $html code string uses, so we can target it
	 *                      in our replacement. For example 'img'.
	 * @param string $class The HTML class we want to add
	 * @return array|string|string[]|null
	 */
	public static function add_class_attr( string $html, string $tag, string $class ) {
		return preg_match( '/class=["\']/i', $html )

			// The HTML code already has a "class=" parameter, so let's just add our class into that.
			? preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1' . $class . ' $2$1', $html )

			// The HTML code doesn't have a "class=" parameter, so let's target the "tag" and add
			// this parameter including our class.
			: preg_replace( '/<' . $tag . '/is', '<' . $tag . ' class="' . $class . '"', $html );
	}

	/**
	 * Adds a css to a html tag.
	 *
	 * @param string $html  The HTML "tag" code we want to modify
	 * @param string $tag   The "tag" the $html code string uses, so we can target it
	 *                      in our replacement. For example 'img'.
	 * @param string $css   The HTML css we want to add
	 * @return array|string|string[]|null
	 */
	public static function add_style_attr( string $html, string $tag, string $css ) {
		return preg_match( '/style=["\']/i', $html )

			// The HTML code already has a "class=" parameter, so let's just add our class into that.
			? preg_replace( '/style=(["\'])(.*?)["\']/is', 'style=$1' . $css . ' $2$1', $html )

			// The HTML code doesn't have a "class=" parameter, so let's target the "tag" and add
			// this parameter including our class.
			: preg_replace( '/<' . $tag . '/is', '<' . $tag . ' style="' . $css . '"', $html );
	}

	/**
	 * Create html attributes
	 *
	 * @param array<string, string> $attributes
	 * @param string                $svg_attr
	 * @return string
	 */
	public static function create_attr( array $attributes, string $svg_attr = '' ): string {
		foreach ( $attributes as $attr => $spec ) {
			$svg_attr .= "{$attr}=\"{$spec}\" ";
		}
		return $svg_attr;
	}
}
