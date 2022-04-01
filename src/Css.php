<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Css {

	/**
	 * Render CSS rules
	 *
	 * @param array<string, mixed> $rules
	 * @return string
	 */
	public static function render( array $rules ): string {
		$css_string = '';
		foreach ( $rules as $rule ) {
			$css_string .= "{$rule['selector']} {";

			foreach ( $rule['declarations'] as $property => $value ) {

				if ( \is_string( $value ) ) {
					$css_string .= "{$property}: {$value};";

				} elseif ( \is_array( $value ) && $property === 'src' ) {
					$count_sources = 0;
					$css_string .= 'src: ';
					foreach ( $value as $format => $url ) {
						$count_sources++;
						$css_string .= "url('{$url}') format('{$format}')";
						$css_string .= \count( $value ) === $count_sources ? ';' : ',';
					}
				}
			}

			$css_string .= '}';
		}
		return $css_string;
	}

	/**
	 * Inject CSS from a file
	 *
	 * @param string $file
	 * @param array  $attr
	 * @return void
	 */
	public static function inject_file_css( string $file, array $attr = [] ) {
		ob_start();
		include $file;
		$inline_css = ob_get_clean();
		static::inject_inline_css( $inline_css, $attr );
	}

	/**
	 * Inject CSS with a style tag
	 *
	 * @param string $inline_css
	 * @param array  $attr
	 * @return void
	 */
	public static function inject_inline_css( string $inline_css, array $attr = [] ) {
		if ( $inline_css != "" ) {
			$attr = Str::create_attr( $attr );
			echo "
<style {$attr}>{$inline_css}</style>
";
		}
	}
}
