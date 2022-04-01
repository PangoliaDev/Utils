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
}
