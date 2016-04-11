<?php

namespace Wikibase\Elastic\Fields;

use InvalidArgumentException;

class WikibaseFieldDefinitions {

	/**
	 * @var string[] Indexed by entity type
	 */
	private $entityTypeSearchFields;

	/**
	 * @var string[]
	 */
	private $enabledFields;

	/**
	 * @var string[]
	 */
	private $languageCodes;

	/**
	 * @param string[] $entityTypeSearchFields
	 * @param string[] $enabledFields
	 * @param string[] $languageCodes
	 */
	public function __construct(
		array $entityTypeSearchFields,
		array $enabledFields,
		array $languageCodes
	) {
		$this->entityTypeSearchFields = $entityTypeSearchFields;
		$this->enabledFields = $enabledFields;
		$this->languageCodes = $languageCodes;
	}

	/**
	 * @return Field[]
	 */
	public function getFieldsForMapping() {
		$fields = array();

		foreach ( $this->entityTypeSearchFields as $entityType => $fieldNames ) {
			$fields = array_merge( $fields, $this->getEnabledFieldsForEntityType( $entityType ) );
		}

		ksort( $fields );

		return $fields;
	}

	/**
	 * @param string $entityType
	 *
	 * @throws InvalidArgumentException
	 * @return Field[]
	 */
	public function getFieldsForIndexing( $entityType ) {
		return $this->getEnabledFieldsForEntityType( $entityType );
	}

	private function getEnabledFieldsForEntityType( $entityType ) {
		if ( !array_key_exists( $entityType, $this->entityTypeSearchFields ) ) {
			throw new \InvalidArgumentException( 'Unknown entityType' );
		}

		$fieldNames = $this->filterEnabledFieldNames( $this->entityTypeSearchFields[$entityType] );

		$fields = array();

		foreach ( $fieldNames as $fieldName ) {
			$fields = array_merge( $fields, $this->expandMultilingualField( $fieldName ) );
		}

		return $fields;
	}

	/**
	 * @param string $fieldName
	 *
	 * @return Field[]
	 */
	private function expandMultilingualField( $fieldName ) {
		$field = $this->newFieldFromType( $fieldName );

		return $field->getProperties();
	}

	/**
	 * @param string[] $fieldNames
	 *
	 * @return string[]
	 */
	private function filterEnabledFieldNames( array $fieldNames ) {
		$fields = array();

		foreach ( $fieldNames as $fieldName ) {
			if ( in_array( $fieldName, $this->enabledFields ) ) {
				$fields[] = $fieldName;
			}
		}

		return $fields;
	}

	/**
	 * @param string $type
	 * @param string $languageCode
	 *
	 * @return Field
	 */
	private function newFieldFromType( $type ) {
		switch( $type ) {
			case 'label':
				return new TermListField(
					$this->languageCodes,
					array( 'all', 'all_near_match' ),
					'label'
				);
			case 'descriptions':
				return new TermListField(
					$this->languageCodes,
					array( 'all' ),
					'description'
				);
			case 'label_count':
				return new LabelCountField();
			case 'sitelink_count':
				return new SiteLinkCountField();
			case 'statement_count':
				return new StatementCountField();
			default:
				throw new InvalidArgumentException( 'Unknown field type: ' . $type );
		}
	}

}
