<?php

namespace Wikibase\Elastic\Tests\Fields;

use Elastica\Document;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\Elastic\Fields\StatementCountField;

/**
 * @covers Wikibase\Elastic\Fields\StatementCountField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class StatementCountFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetPropertyDefinition() {
		$field = new StatementCountField();

		$this->assertSame( [ 'type' => 'long' ], $field->getPropertyDefinition() );
	}

	/**
	 * @dataProvider doIndexProvider
	 */
	public function testDoIndex( $expected, $entity ) {
		$document = new Document();

		$field = new StatementCountField();
		$field->doIndex( $entity, $document );

		$this->assertSame( $expected, $document->getData() );
	}

	public function doIndexProvider() {
		$item = new Item();
		$item->getStatements()->addNewStatement(
			new PropertyNoValueSnak( new PropertyId( 'P1' ) )
		);

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( [ 'statement_count' => 1 ], $item ),
			array( [ 'statement_count' => 0 ], $emptyProperty ),
		);
	}

}
