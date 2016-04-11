<?php

namespace Wikibase\Elastic;

use Elastica\Document;
use Elastica\Type;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\Property;

class EntityIndexer {

	/**
	 * @var Type
	 */
	private $type;

	/**
	 * @var string[]
	 */
	private $languageCodes;

	/**
	 * @param Type $type
	 * @param string[] $languageCodes
	 */
	public function __construct( Type $type, array $languageCodes ) {
		$this->type = $type;
		$this->languageCodes = $languageCodes;
	}

	/**
	 * @param EntityDocument $entity
	 * @oaram Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$searchFieldDefinitions = $this->getSearchFieldDefinitionsForEntityType( $entity->getType() );
		$searchFields = $searchFieldDefinitions->getSearchFields();

		foreach ( $searchFields as $fieldName => $field ) {
			if ( $fieldName === 'label' ) {
				$field->doIndex( $entity->getFingerprint()->getLabels(), $document );
			} else if ( $fieldName === 'description' ) {
				$field->doIndex( $entity->getFingerprint()->getDescriptions(), $document );
			} else {
				$field->doIndex( $entity, $document );
			}
		}

		// @todo upsert
		$this->type->addDocument( $document );
	}

	private function getSearchFields() {
		$searchFieldDefinitions = $this->getSearchFieldDefinitions();
		$fields = [];

		foreach ( $searchFieldDefinitions as $type => $class ) {
			$definition = new $class( $this->languageCodes );
			$fields = array_unique( array_merge( $fields, $definition->getSearchFields() ) );
		}

		ksort( $searchFields );

		return $searchFields;
	}

	private function getSearchFieldDefinitions() {
		return [
			Item::ENTITY_TYPE => 'Wikibase\Elastic\Fields\ItemSearchFieldDefinitions',
			Property::ENTITY_TYPE => 'Wikibase\Elastic\Fields\PropertySearchFieldDefinitions'
		];
	}

	private function getSearchFieldDefinitionsForEntityType( $entityType ) {
		$searchFieldDefinitions = $this->getSearchFieldDefinitions();

		if ( !isset( $searchFieldDefinitions[$entityType] ) ) {
			throw new \UnexpectedValueException( $entityType . ' is unknown' );
		}

		return new $searchFieldDefinitions[$entityType]( $this->languageCodes );
	}

}
