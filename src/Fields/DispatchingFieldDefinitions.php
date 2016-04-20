<?php

namespace Wikibase\Elastic\Fields;

class DispatchingFieldDefinitions implements FieldDefinitions {

	private $fieldDefinitions;

	private $languageCodes;

	public function __construct( array $fieldDefinitions, array $languageCodes ) {
		$this->fieldDefinitions = $fieldDefinitions;
		$this->languageCodes = $languageCodes;
	}

	/**
	 * @return Field[]
	 */
	public function getFields() {
		$fields = array();

		foreach ( $this->fieldDefinitions as $type => $class ) {
			$fieldDefinition = new $class( $this->languageCodes );

			foreach ( $fieldDefinition->getFields() as $name => $field ) {
				$fields[$name] = $field;
			}
		}

		return $fields;
	}

}
