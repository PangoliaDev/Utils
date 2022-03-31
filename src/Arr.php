<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Arr {

	/**
	 * Move existing key in array to the first position.
	 *
	 * @param string[]|array<string|int, mixed> $array The array to transform.
	 * @param string|int                        $key   The key to prepend.
	 * @return array<string|int, mixed>
	 * @since 0.1.0
	 */
	public static function set_key_first( array $array, $key ): array {
		$insert = [ $key => $array[ $key ] ];
		unset( $array[ $key ] );
		return \array_merge( $insert, $array );
	}

	/**
	 * Move multiple existing keys in array to the first position. If key is not found
	 * then it will try to find a matching value using array_search.
	 *
	 * @param string[]|array<string|int, mixed> $array The array to transform.
	 * @param string[]                          $keys  The keys to move.
	 * @return array<string|int, mixed>
	 * @since 0.1.0
	 */
	public static function set_keys_first( array $array, array $keys ): array {
		foreach ( $keys as $key ) {
			$key = isset( $array[ $key ] )
				? $key
				: \array_search( $key, $array ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

			if ( $key === false || $key === null ) {
				continue;
			}

			$array = static::set_key_first( $array, $key );
		}
		return $array;
	}

	/**
	 * Move existing keys found by strpos in array to the first position.
	 * If key is numeric then value should be a string to make strpos work.
	 *
	 * @param string[]|array<string|int, mixed> $array   The array to transform.
	 * @param string[]                          $needles The needles.
	 * @return array<string|int, mixed>
	 * @since 0.1.0
	 */
	public static function set_strpos_keys_first( array $array, array $needles ): array {
		foreach ( $needles as $needle ) {
			foreach ( $array as $key => $value ) {
				if ( \strpos( is_numeric( $key ) ? $value : $key, $needle ) !== false ) {
					$array = static::set_key_first(
						$array, $key
					);
					break;
				}
			}
		}
		return $array;
	}

	/**
	 * Converts value to array key.
	 *
	 * @param mixed $value
	 * @return int|string|null
	 * @since 0.1.0
	 */
	public static function to_key( $value ) {
		return \key( [ $value => null ] );
	}

	/**
	 * Returns zero-indexed position of given array key. Returns null if key is not found.
	 *
	 * @param array $array <string|int, mixed>
	 * @param       $key
	 * @return false|int|string|null
	 * @since 0.1.0
	 */
	public static function get_key_offset( array $array, $key ) {
		return \array_search( static::to_key( $key ), \array_keys( $array ), true ) === false ?
			null : \array_search( static::to_key( $key ), \array_keys( $array ), true );
	}

	/**
	 * Inserts the contents of the $inserted array into the $array immediately after the $key.
	 * If $key is null (or does not exist), it is inserted at the beginning.
	 *
	 * @param array<string|int, mixed> $array
	 * @param string|int               $key
	 * @param array<string|int, mixed> $inserted
	 * @since 0.1.0
	 */
	public static function insert_before( array &$array, $key, array $inserted ) {
		$offset = $key === null ? 0 : (int) static::get_key_offset( $array, $key );
		$array = \array_slice( $array, 0, $offset, true )
			+ $inserted
			+ \array_slice( $array, $offset, \count( $array ), true );
	}

	/**
	 * Inserts the contents of the $inserted array into the $array before the $key.
	 * If $key is null (or does not exist), it is inserted at the end.
	 *
	 * @param array<string|int, mixed> $array
	 * @param string|int               $key
	 * @param array<string|int, mixed> $inserted
	 * @since 0.1.0
	 */
	public static function insert_after( array &$array, $key, array $inserted ) {
		$offset = static::get_key_offset( $array, $key );

		if ( $key === null || ( $offset ) === null ) {
			$offset = \count( $array ) - 1;
		}
		$array = \array_slice( $array, 0, $offset + 1, true )
			+ $inserted
			+ \array_slice( $array, $offset + 1, \count( $array ), true );
	}
}
