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
				return static::is_single();
			case 'post' :
				return static::is_post();
			case 'product' :
				return static::is_product();
			case 'archive' :
				return static::is_archive();
			case 'home' :
				return static::is_home();
			case 'page' :
				return static::is_page();
			case 'search' :
				return static::is_search();
			case '404' :
				return static::is_404();
			case 'logged_in' :
				return static::is_logged_in();
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

	/**
	 * We're doing a single page request
	 *
	 * @return bool
	 */
	public static function is_single(): bool {
		return \is_single();
	}

	/**
	 * We're doing a single post request
	 *
	 * @return bool
	 */
	public static function is_post(): bool {
		return \is_singular( 'post' );
	}

	/**
	 * We're doing a single product request
	 *
	 * @return bool
	 */
	public static function is_product(): bool {
		return \is_singular( 'product' );
	}

	/**
	 * We're doing an archive request
	 *
	 * @return bool
	 */
	public static function is_archive(): bool {
		return \is_archive();
	}

	/**
	 * We're doing a home page request
	 *
	 * @return bool
	 */
	public static function is_home(): bool {
		return \is_front_page();
	}

	/**
	 * We're doing a search page request
	 *
	 * @return bool
	 */
	public static function is_search(): bool {
		return \is_search();
	}

	/**
	 * We're doing a 404 page request
	 *
	 * @return bool
	 */
	public static function is_404(): bool {
		return \is_404();
	}

	/**
	 * We're doing a user logged in request
	 *
	 * @return bool
	 */
	public static function is_logged_in(): bool {
		return \is_user_logged_in();
	}

	/**
	 * We're doing a page request
	 *
	 * @param int|string|int[]|string[] $page Optional. Page ID, title, slug, or array of such
	 *                                        to check against. Default empty.
	 * @return bool
	 */
	public static function is_page( $page = '' ): bool {
		return \is_page( $page );
	}
}
