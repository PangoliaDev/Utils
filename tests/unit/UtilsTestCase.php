<?php

namespace Pangolia\UtilsTests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;

class UtilsTestCase extends TestCase {

	/**
	 * Setup which calls \WP_Mock setup
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Monkey\Functions\when( '__' )->returnArg( 1 );
		Monkey\Functions\when( '_e' )->returnArg( 1 );
		Monkey\Functions\when( '_n' )->returnArg( 1 );
	}

	/**
	 * Teardown which calls \WP_Mock tearDown
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}