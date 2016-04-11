<?php

namespace Wikibase\Elastic\Tests\Fields;

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
		$labelField = new LabelField( 'ja' );

		$expected = array(
			'type' => 'string',
			'copy_to' => array( 'all', 'all_near_match' )
		);

		$this->assertSame( $expected, $labelField->getPropertyDefinition() );
	}

	/**
	 * @dataProvider hasFieldDataProvider
	 */
	public function testHasFieldData( $expected, $languageCode, $entity ) {
		$labelField = new LabelField( $languageCode );

		$this->assertSame( $expected, $labelField->hasFieldData( $entity ) );
	}

	public function hasFieldDataProvider() {
		$item = new Item();
		$item->setLabel( 'en', 'kitten' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( true, 'en', $item ),
			array( false, 'es', $item ),
			array( false, 'en', $emptyProperty ),
		);
	}

	public function testGetFieldData() {
		$item = new Item();
		$item->setLabel( 'en', 'kitten' );

		$labelField = new LabelField( 'en' );
		$this->assertSame( 'kitten', $labelField->getFieldData( $item ) );
	}

	public function testGetFieldData_labelNotFound() {
		$labelField = new LabelField( 'es' );

		$this->setExpectedException( 'OutOfBoundsException' );
		$labelField->getFieldData( new Item() );
	}

}
