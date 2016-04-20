<?php

namespace Wikibase\Elastic;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\Elastic\Fields\FieldDefinitions;

class EntityFieldsIndexer {

	/**
	 * @var FieldDefinitions
	 */
	private $fieldDefinitions;

	/**
	 * @param FieldDefinitions $fieldDefinitions
	 */
	public function __construct( FieldDefinitions $fieldDefinitions ) {
		$this->fieldDefinitions = $fieldDefinitions;
	}

	/**
	 * @param EntityDocument $entity
	 * @param Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$fields = $this->fieldDefinitions->getFields();

		foreach ( $fields as $fieldName => $field ) {
			if ( $fieldName === 'label' ) {
				$field->doIndex( $entity->getFingerprint()->getLabels(), $document );
			} elseif ( $fieldName === 'description' ) {
				$field->doIndex( $entity->getFingerprint()->getDescriptions(), $document );
			} else {
				$field->doIndex( $entity, $document );
			}
		}
	}

}
