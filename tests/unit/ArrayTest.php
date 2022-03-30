<?php

namespace Pangolia\UtilsTests\Unit;

use Pangolia\Utils\Arr;

class ArrayTest extends UtilsTestCase {
	public function testSetKeyFirst() {
		$stringKeyArray = [
			'first'  => 'firstArrayValue',
			'second' => 'secondArrayValue',
			'third'  => 'thirdArrayValue',
		];
		$newStringKeyArray = Arr::set_key_first( $stringKeyArray, 'third' );
		$numericKeyArray = [
			1 => 'firstArrayValue',
			2 => 'secondArrayValue',
			3 => 'thirdArrayValue',
		];
		$newNumericKeyArray = Arr::set_key_first( $numericKeyArray, 3 );
		$this->assertSame( $stringKeyArray['third'], $newStringKeyArray[ array_key_first( $newStringKeyArray ) ] );
		$this->assertSame( $numericKeyArray[3], $newNumericKeyArray[ array_key_first( $newNumericKeyArray ) ] );
	}
}