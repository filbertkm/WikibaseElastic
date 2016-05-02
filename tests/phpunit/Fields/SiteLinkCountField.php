<?php

namespace Wikibase\Elastic\Tests\Fields;

use Elastica\Document;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;
use Wikibase\Elastic\Fields\SiteLinkCountField;

/**
 * @covers Wikibase\Elastic\Fields\SiteLinkCountField
 *
 * @group WikibaseElastic
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SiteLinkCountFieldTest extends PHPUnit_Framework_TestCase {

	public function testGetPropertyDefinition() {
		$field = new SiteLinkCountField();

		$this->assertSame(
			[ 'type' => 'integer' ],
			$field->getPropertyDefinition()
		);
	}

	/**
	 * @dataProvider doIndexProvider
	 */
	public function testDoIndex( $expected, $entity ) {
		$document = new Document();

		$field = new SiteLinkCountField();
		$field->doIndex( $entity, $document );

		$this->assertSame( $expected, $document->getData() );
	}

	public function doIndexProvider() {
		$item = new Item();
		$item->getSiteLinkList()->addNewSiteLink( 'enwiki', 'kitten' );

		$emptyProperty = Property::newFromType( 'string' );

		return array(
			array( [ 'sitelink_count' => 1 ], $item ),
			array( [ 'sitelink_count' => 0 ], $emptyProperty ),
		);
	}

}
