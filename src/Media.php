<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

/**
 * Collection of WP media/attachment helpers.
 *
 * @since 0.1.0
 */
class Media {

	/**
	 * Tries to convert an attachment URL into a post ID.
	 *
	 * This code is a bit heavy, so you probably want to cache the results somehow.
	 *
	 * @param string $url  The URL to resolve.
	 * @return int The found post ID, or 0 on failure.
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 */
	public static function get_image_id_by_url( string $url ): int {
		global $wpdb;

		$dir = \wp_get_upload_dir();
		$path = $url;

		$site_url = \parse_url( $dir['url'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
		$image_path = \parse_url( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url

		// Force the protocols to match if needed.
		if ( isset( $image_path['scheme'] ) && ( $image_path['scheme'] !== $site_url['scheme'] ) ) {
			$path = \str_replace( $image_path['scheme'], $site_url['scheme'], $path );
		}

		if ( 0 === \strpos( $path, $dir['baseurl'] . '/' ) ) {
			$path = \substr( $path, \strlen( $dir['baseurl'] . '/' ) );
		}

		$results = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
			$path
		) );
		$post_id = null;

		if ( $results ) {
			// Use the first available result, but prefer a case-sensitive match, if exists.
			$post_id = \reset( $results )->post_id;

			if ( \count( $results ) > 1 ) {
				foreach ( $results as $result ) {
					if ( $path === $result->meta_value ) {
						$post_id = $result->post_id;
						break;
					}
				}
			}
		}

		return (int) $post_id;
	}

	/**
	 * Get the attributes for srcset and sizes as a string
	 *
	 * @param int|string $attachment_id
	 * @param string     $size
	 * @return false|array
	 */
	public static function get_image_attr( $attachment_id, string $size = 'full' ) {
		$image_attr = static::get_image_src( $attachment_id, $size );
		$image_meta = wp_get_attachment_metadata( $attachment_id );

		if ( $image_attr === false || $image_meta === false ) {
			return false;
		}

		$calculations = static::get_image_calculations(
			[
				absint( $image_attr['width'] ),
				absint( $image_attr['height'] ),
			],
			$image_attr['src'],
			$image_meta,
			$attachment_id
		);

		return [
			'src'    => $image_attr['src'] ?? '',
			'srcset' => $calculations['srcset'] ?? '',
			'sizes'  => $calculations['sizes'] ?? '',
		];
	}

	/**
	 * Returns wp_get_attachment_image_src as a formatted array
	 *
	 * @param int|string $attachment_id
	 * @param string     $size
	 * @return array|false
	 */
	public static function get_image_src( $attachment_id, string $size = 'full' ) {
		$image = wp_get_attachment_image_src( $attachment_id, $size );

		if ( $image === false ) {
			return false;
		}

		list( $src, $width, $height, $resized ) = $image;
		return [
			'src'     => $src,
			'width'   => $width,
			'height'  => $height,
			'resized' => $resized,
		];
	}

	/**
	 * A helper function to calculate the image sources to include in a 'srcset' & 'sizes' attribute.
	 *
	 * @param int[]  $size_array    {
	 *
	 * An array of width and height values.
	 *
	 * @type int $0 The width in pixels.
	 * @type int $1 The height in pixels.
	 * }
	 * @param string $image_src     The 'src' of the image.
	 * @param array<int|string, mixed>  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id Optional. The image attachment ID. Default 0.
	 * @return array<int|string, mixed>
	 */
	public static function get_image_calculations( array $size_array, string $image_src, array $image_meta, int $attachment_id ): array {
		return [
			'srcset' => wp_calculate_image_srcset( $size_array, $image_src, $image_meta, $attachment_id ),
			'sizes'  => wp_calculate_image_sizes( $size_array, $image_src, $image_meta, $attachment_id ),
		];
	}

	/**
	 * Replaces the extension to a webp extension.
	 *
	 * @param string|string[] $subject
	 * @return array|string|string[]
	 */
	public static function replace_ext_to_webp( $subject ) {
		return str_replace(
			[ '.jpg', '.png', '.jpeg', '.gif' ],
			[ '.webp', '.webp', '.webp', '.webp' ],
			$subject
		);
	}

	/**
	 * Calculate aspect ratio based on height and width
	 *
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	public static function calc_aspect_ratio( int $width, int $height ): string {
		// search for greatest common divisor
		$greatest_common_divisor = static function ( $width, $height ) use ( &$greatest_common_divisor ) {
			return ( $width % $height ) ? \call_user_func( $greatest_common_divisor, $height, $width % $height ) : $height;
		};

		$divisor = \call_user_func( $greatest_common_divisor, $width, $height );

		return $width / $divisor . '/' . $height / $divisor;
	}
}
