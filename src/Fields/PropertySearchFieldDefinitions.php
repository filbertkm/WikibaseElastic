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
		$fieldNames = [ 'label_count', 'statement_count', 'label', 'description' ];
		$fields = [];

		foreach ( $fieldNames as $fieldName ) {
			$fields[$fieldName] = $this->getField( $fieldName );
		}

		return $fields;
	}

	private function getField( $fieldName ) {
		switch( $fieldName ) {
			case 'label_count':
				return new LabelCountField();
			case 'statement_count':
				return new StatementCountField();
			case 'label':
				return new TermListField( $this->languageCodes, array( 'all', 'all_near_match' ), 'label' );
			case 'description':
				return new TermListField( $this->languageCodes, array( 'all' ), 'description' );
			default:
				throw new \UnexpectedValueException( $fieldName . ' is unknown' );
		}
	}

}
