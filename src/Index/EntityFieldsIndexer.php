<?php

namespace Wikibase\Elastic\Index;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;

class EntityFieldsIndexer {

	/**
	 * @var FieldDefinitions[]
	 */
	private $fieldDefinitions;

	/**
	 * @param FieldDefinitions[] $fieldDefinitions
	 */
	public function __construct( array $fieldDefinitions ) {
		$this->fieldDefinitions = $fieldDefinitions;
	}

	/**
	 * @param EntityDocument $entity
	 * @param Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$entityType = $entity->getType();

		if ( !array_key_exists( $entityType, $this->fieldDefinitions ) ) {
			throw new \UnexpectedValueException( 'Unknown entity type: ' . $entityType );
		}

		$this->fieldDefinitions[$entityType]->doIndex( $entity, $document );
	}

}
