<?php

namespace Wikibase\Elastic\Tests\Fields;

use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\Elastic\Fields\WikibaseFieldDefinitions;

/**
 * @covers Wikibase\Elastic\Fields\WikibaseFieldDefinitions
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class WikibaseFieldDefinitionsTest extends PHPUnit_Framework_TestCase {

	public function testGetFieldsForMapping() {
		$wikibaseFieldDefinitions = new WikibaseFieldDefinitions(
			$this->getEntitySearchFields(),
			array( 'labels' ),
			array( 'ar', 'en', 'es' )
		);

		$fields = $wikibaseFieldDefinitions->getFieldsForMapping();

		$expectedFieldNames = array( 'label_ar', 'label_en', 'label_es' );

		$this->assertSame( $expectedFieldNames, array_keys( $fields ) );
	}

	public function testGetFieldsForMapping_instanceOfField() {
		$wikibaseFieldDefinitions = new WikibaseFieldDefinitions(
			$this->getEntitySearchFields(),
			array( 'labels' ),
			array( 'de', 'es', 'ja' )
		);

		foreach ( $wikibaseFieldDefinitions->getFieldsForMapping() as $fieldName => $field ) {
			$this->assertInstanceOf(
				'Wikibase\Elastic\Fields\Field',
				$field,
				"$fieldName must be instance of Field"
			);
		}
	}

	public function testGetFieldsForIndexing() {
		$wikibaseFieldDefinitions = new WikibaseFieldDefinitions(
			$this->getEntitySearchFields(),
			array( 'descriptions' ),
			array( 'es', 'fr' )
		);

		$fields = $wikibaseFieldDefinitions->getFieldsForIndexing( Item::ENTITY_TYPE );
		$expectedFieldNames = array( 'description_es', 'description_fr' );

		$this->assertSame( $expectedFieldNames, array_keys( $fields ) );
	}

	private function getEntitySearchFields() {
		return array(
			Item::ENTITY_TYPE => array( 'labels', 'descriptions' ),
			Property::ENTITY_TYPE => array( 'labels', 'descriptions' )
		);
	}

}
