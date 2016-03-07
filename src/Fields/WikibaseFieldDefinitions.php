<?php

namespace Wikibase\Elastic\Fields;

use InvalidArgumentException;
use Wikibase\Elastic\EntityFieldsMapping;

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

		sort( $fields );

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
		$fields = array();

		foreach ( $this->languageCodes as $languageCode ) {
			$field = $this->newFieldFromType( $fieldName, $languageCode );
			$name = $field->getFieldName();


			$fields[$name] = $field;
		}

		return $fields;
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
	private function newFieldFromType( $type, $languageCode ) {
		switch( $type ) {
			case 'labels':
				return new LabelField( $languageCode );
			case 'descriptions':
				return new DescriptionField( $languageCode );
			default:
				throw new InvalidArgumentException( 'Unknown field type: ' . $type );
		}
	}

}
