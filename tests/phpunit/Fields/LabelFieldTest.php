<?php

namespace Wikibase\Elastic\Tests\Fields;

use Elastica\Document;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\Elastic\Fields\LabelField;

/**
 * @covers Wikibase\Elastic\Fields\LabelField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class LabelFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetFieldName() {
		$labelField = new LabelField( 'es' );

		$this->assertSame( 'label_es', $labelField->getFieldName() );
	}

	public function testGetMapping() {
		$labelField = new LabelField( 'ja', array( 'all', 'all_near_match' ) );

		$expected = array(
			'type' => 'string',
			'copy_to' => array( 'all', 'all_near_match' )
		);

		$this->assertSame( $expected, $labelField->getPropertyDefinition() );
	}

	/**
	 * @dataProvider doIndexProvider
	 */
	public function testDoIndex( $expected, $languageCode, $entity ) {
		$document = new Document();

		$labelField = new LabelField( $languageCode );
		$labelField->doIndex( $entity, $document );

		$this->assertSame( $expected, $document->getData() );
	}

	public function doIndexProvider() {
		$item = new Item();
		$item->setLabel( 'en', 'kitten' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( array( 'label_en' => 'kitten' ), 'en', $item ),
			array( array(), 'es', $item ),
			array( array(), 'en', $emptyProperty ),
		);
	}

}
