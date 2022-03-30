<?php

namespace Pangolia\UtilsTests\Unit;

use Pangolia\Utils\Media;

class MediaTest extends UtilsTestCase {
	public function testReplaceExtToWebp() {
		foreach ( [ 'jpg', 'png', 'jpeg', 'gif' ] as $ext ) {
			$this->assertSame( 'my-image-file.webp', Media::replace_ext_to_webp( "my-image-file.{$ext}" ) );
			$this->assertSame( 'my-image-file.webp', Media::replace_ext_to_webp( ["my-image-file.{$ext}"] )[0] );
		}
	}

	public function testCalcAspectRatio() {
		$this->assertSame( '3/4', Media::calc_aspect_ratio( 150, 200 ) );
		$this->assertSame( '16/3', Media::calc_aspect_ratio( 800, 150 ) );
		$this->assertSame( '400/111', Media::calc_aspect_ratio( '1200', '333' ) );
		$this->assertSame( '841/333', Media::calc_aspect_ratio( '841', '333' ) );
	}
}