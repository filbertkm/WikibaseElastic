<?php

namespace Wikibase\Elastic\Tests\Fields;

use Elastica\Document;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\Elastic\Fields\DescriptionField;

/**
 * @covers Wikibase\Elastic\Fields\DescriptionField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class DescriptionFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetFieldName() {
		$descriptionField = new DescriptionField( 'es' );

		$this->assertSame( 'description_es', $descriptionField->getFieldName() );
	}

	public function testGetMapping() {
		$descriptionField = new DescriptionField( 'ja', array( 'all' ) );

		$expected = array(
			'type' => 'string',
			'copy_to' => array( 'all' )
		);

		$this->assertSame( $expected, $descriptionField->getPropertyDefinition() );
	}

	/**
	 * @dataProvider doIndexProvider
	 */
	public function testDoIndex( $expected, $languageCode, $entity ) {
		$document = new Document();

		$descriptionField = new DescriptionField( $languageCode );
		$descriptionField->doIndex( $entity, $document );

		$this->assertSame( $expected, $document->getData() );
	}

	public function doIndexProvider() {
		$item = new Item();
		$item->setDescription( 'en', 'young cat' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( array( 'description_en' => 'young cat' ), 'en', $item ),
			array( array(), 'es', $item ),
			array( array(), 'en', $emptyProperty ),
		);
	}

}
