<?php

namespace Wikibase\Elastic\Mapping;

use Elastica\Type;
use Elastica\Type\Mapping;
use Wikibase\Elastic\Fields\FieldDefinitions;

class WikibaseMappingBuilder {

	private $fieldDefinitions;

	public function __construct( FieldDefinitions $fieldDefinitions ) {
		$this->fieldDefinitions = $fieldDefinitions;
	}

	/**
	 * @param Type $type
	 * @param Field[] $allFields key (name of field) => value
	 *
	 * @return Mapping
	 */
	public function build( Type $type, array $allFields = [] ) {
		$mapping = new Mapping( $type, $this->getProperties( $allFields ) );

		$mapping->setAllField( [
			'enabled' => false
		] );

		return $mapping;
	}

	public function getProperties( array $allFields = [] ) {
		$properties = [];

		foreach ( $allFields as $name => $field ) {
			$properties[$name] = $field->getPropertyDefinition();
		}


		$fields = $this->fieldDefinitions->getFields();

		foreach ( $fields as $name => $field ) {
			$properties[$name] = $field->getPropertyDefinition();
		}

		return $properties;
	}

}
