<?php

namespace Wikibase\Elastic\Mapping;

use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

class WikibaseMappingBuilder {

	/**
	 * @var string[]
	 */
	private $languageCodes;

	public function __construct( array $languageCodes ) {
		$this->languageCodes = $languageCodes;
	}

	public function getProperties() {
		$fields = $this->getSearchFields();

		$properties = [];

		foreach ( $fields as $name => $field ) {
			$properties[$name] = $field->getPropertyDefinition();
		}

		return $properties;
	}

	private function getSearchFields() {
		$searchFieldDefinitions = [
			Item::ENTITY_TYPE => 'Wikibase\Elastic\Fields\ItemSearchFieldDefinitions',
			Property::ENTITY_TYPE => 'Wikibase\Elastic\Fields\PropertySearchFieldDefinitions'
		];

		$fields = [];

		foreach ( $searchFieldDefinitions as $type => $class ) {
			$definition = new $class( $this->languageCodes );
			$searchFields = $definition->getSearchFields();

			foreach ( $searchFields as $key => $searchField ) {
				$fields[$key] = $searchField;
			}
		}

		return $fields;
	}

}