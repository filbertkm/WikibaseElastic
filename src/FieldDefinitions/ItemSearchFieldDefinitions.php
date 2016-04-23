<?php

namespace Wikibase\Elastic\Fields;

use Wikibase\Elastic\Fields\DescriptionField;
use Wikibase\Elastic\Fields\Field;
use Wikibase\Elastic\Fields\LabelCountField;
use Wikibase\Elastic\Fields\LabelField;
use Wikibase\Elastic\Fields\SiteLinkCountField;
use Wikibase\Elastic\Fields\StatementCountField;

class ItemSearchFieldDefinitions implements FieldDefinitions {

	/**
	 * @var string[]
	 */
	private $languageCodes;

	/**
	 * @param string[] $languageCodes
	 */
	public function __construct( array $languageCodes ) {
		$this->languageCodes = $languageCodes;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		$fields = [
			'label_count' => new LabelCountField(),
			'sitelink_count' => new SiteLinkCountField(),
			'statement_count' => new StatementCountField()
		];

		foreach ( $this->languageCodes as $languageCode ) {
			$field = new LabelField( $languageCode, array( 'all', 'all_near_match' ) );
			$fields[$field->getFieldName()] = $field;

			$field = new DescriptionField( $languageCode, array( 'all' ) );
			$fields[$field->getFieldName()] = $field;
		}

		return $fields;
	}

}