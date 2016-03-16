<?php

namespace Wikibase\Elastic\Tests\Fields;

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
		$descriptionField = new DescriptionField( 'ja' );

		$expected = array(
			'type' => 'string',
			'copy_to' => array( 'all' )
		);

		$this->assertSame( $expected, $descriptionField->getMapping() );
	}

	/**
	 * @dataProvider hasFieldDataProvider
	 */
	public function testHasFieldData( $expected, $languageCode, $entity ) {
		$descriptionField = new DescriptionField( $languageCode );

		$this->assertSame( $expected, $descriptionField->hasFieldData( $entity ) );
	}

	public function hasFieldDataProvider() {
		$item = new Item();
		$item->setDescription( 'en', 'young cat' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( true, 'en', $item ),
			array( false, 'es', $item ),
			array( false, 'en', $emptyProperty ),
		);
	}

	public function testGetFieldData() {
		$item = new Item();
		$item->setDescription( 'en', 'young cat' );

		$descriptionField = new DescriptionField( 'en' );
		$this->assertSame( 'young cat', $descriptionField->getFieldData( $item ) );
	}

	public function testGetFieldData_descriptionNotFound() {
		$descriptionField = new DescriptionField( 'es' );

		$this->setExpectedException( 'OutOfBoundsException' );
		$descriptionField->getFieldData( new Item() );
	}

}
