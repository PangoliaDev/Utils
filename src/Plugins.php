<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Plugins {
	/**
	 * Determines if the plugin is active
	 *
	 * @param string $plugin
	 * @return bool
	 */
	public static function is_active( string $plugin ): bool {
		return \in_array( $plugin, (array) \get_option( 'active_plugins', array() ) );
	}
}
