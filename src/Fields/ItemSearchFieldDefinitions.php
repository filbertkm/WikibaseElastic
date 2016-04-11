<?php

namespace Wikibase\Elastic\Fields;

class ItemSearchFieldDefinitions {

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
		$fieldNames = [ 'label_count', 'sitelink_count', 'statement_count', 'label', 'description' ];
		$fields = [];

		foreach ( $fieldNames as $fieldName ) {
			$fields[$fieldName] = $this->getField( $fieldName );
		}

		return $fields;
	}

	private function isMultilingualField( $fieldName ) {
		return in_array( $fieldName, array( 'label', 'description' ) );
	}

	private function getField( $fieldName, $languageCode = null ) {
		switch( $fieldName ) {
			case 'label_count':
				return new LabelCountField();
			case 'sitelink_count':
				return new SiteLinkCountField();
			case 'statement_count':
				return new StatementCountField();
			case 'label':
				return new TermListField( $this->languageCodes, array( 'all', 'all_near_match' ), 'label' );
			case 'description':
				return new TermListField( $this->languageCodes, array( 'all' ), 'description' );
			default:
				throw new UnexpectedValueException( $fieldName . ' is unknown' );
		}
	}

}
