<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Env {

	/**
	 * What env is this?
	 *
	 * @param string $env
	 * @return bool
	 */
	public static function is( string $env ): bool {
		switch ( $env ) :
			case 'frontend' :
				return static::is_frontend();
			case 'backend' :
				return static::is_backend();
			case 'cli' :
				return static::is_cli();
			case 'cron' :
				return static::is_cron();
			case 'rest' :
				return static::is_rest();
			case 'ajax' :
				return static::is_ajax();
			case 'single' :
				return \is_single();
			case 'post' :
				return \is_singular( 'post' );
			case 'product' :
				return \is_singular( 'product' );
			case 'archive' :
				return \is_archive();
			case 'home' :
				return \is_front_page();
			default :
				return false;
		endswitch;
	}

	/**
	 * We're on the front-end of the site
	 *
	 * @return bool
	 */
	public static function is_frontend(): bool {
		return \is_admin() && \wp_doing_ajax()
			|| ! \is_admin()
			&& ! \defined( 'REST_REQUEST' )
			&& ! \defined( 'WP_CLI' )
			&& ! \defined( 'DOING_CRON' );
	}

	/**
	 * We're on the back-end of the site
	 *
	 * @return bool
	 */
	public static function is_backend(): bool {
		return \is_admin();
	}

	/**
	 * We're doing CLI commands
	 *
	 * @return bool
	 */
	public static function is_cli(): bool {
		return \defined( 'WP_CLI' ) && WP_CLI;
	}

	/**
	 * We're doing cron jobs
	 *
	 * @return bool
	 */
	public static function is_cron(): bool {
		return \defined( 'DOING_CRON' ) || ( \function_exists( 'wp_doing_cron' ) && \wp_doing_cron() );
	}

	/**
	 * We're doing a REST request
	 *
	 * @return bool
	 */
	public static function is_rest(): bool {
		return \defined( 'REST_REQUEST' );
	}

	/**
	 * We're doing a AJAX request
	 *
	 * @return bool
	 */
	public static function is_ajax(): bool {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}
}
