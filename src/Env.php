<?php
declare( strict_types = 1 );

namespace Pangolia\Utils;

class Env {

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
