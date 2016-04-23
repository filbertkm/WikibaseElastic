<?php

namespace Wikibase\Elastic\Tests\Fields;

use PHPUnit_Framework_TestCase;
use Wikibase\Elastic\Fields\AllField;

/**
 * @covers Wikibase\Elastic\Fields\AllField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class AllFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetPropertyDefinition() {
		$field = new AllField();

		$this->assertSame( [ 'type' => 'string' ], $field->getPropertyDefinition() );
	}

}
