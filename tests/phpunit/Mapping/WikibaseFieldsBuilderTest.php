<?php

namespace Wikibase\Elastic\Tests\Mapping;

use Wikibase\Elastic\Mapping\WikibaseFieldsBuilder;

class WikibaseFieldsBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testGetFields() {
		$fieldsBuilder = new WikibaseFieldsBuilder( array( 'ar', 'es' ) );
		$fields = $fieldsBuilder->getFields();

		$expected = array(
			'labels' => array(
				'type' => 'nested',
				'properties' => array(
					'label_ar' => array(
						'type' => 'string'
					),
					'label_es' => array(
						'type' => 'string'
					)
				)
			),
			'descriptions' => array(
				'type' => 'nested',
				'properties' => array(
					'description_ar' => array(
						'type' => 'string'
					),
					'description_es' => array(
						'type' => 'string'
					)
				)
			),
			'entity_type' => array(
				'type' => 'string'
			),
			'sitelink_count' => array(
				'type' => 'long'
			)
		);

		$this->assertSame( $expected, $fields );
	}

}
