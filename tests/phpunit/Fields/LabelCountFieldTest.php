<?php

namespace Wikibase\Elastic\Tests\Fields;

use Elastica\Document;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\Elastic\Fields\LabelCountField;

/**
 * @covers Wikibase\Elastic\Fields\LabelCountField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class LabelCountFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetPropertyDefinition() {
		$field = new LabelCountField();

		$this->assertSame( [ 'type' => 'integer' ], $field->getPropertyDefinition() );
	}

	/**
	 * @dataProvider doIndexProvider
	 */
	public function testDoIndex( $expected, $entity ) {
		$document = new Document();

		$labelField = new LabelCountField();
		$labelField->doIndex( $entity, $document );

		$this->assertSame( $expected, $document->getData() );
	}

	public function doIndexProvider() {
		$item = new Item();
		$item->setLabel( 'en', 'kitten' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( [ 'label_count' => 1 ], $item ),
			array( [ 'label_count' => 0 ], $emptyProperty ),
		);
	}

}
