<?php

namespace Wikibase\Elastic\Fields;

class PropertySearchFieldDefinitions {

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

	public function getSearchFields() {
		$fields = [
			'label_count' => new LabelCountField(),
			'statement_count' => new StatementCountField()
		];

		foreach ( $this->languageCodes as $languageCode ) {
			$field = new LabelField( $languageCode, array( 'all' ) );
			$fields[$field->getFieldName()] = $field;

			$field = new DescriptionField( $languageCode, array( 'all' ) );
			$fields[$field->getFieldName()] = $field;
		}

		return $fields;
	}

}
