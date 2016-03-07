<?php

namespace Wikibase\Elastic;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Term\FingerprintHolder;

class EntityIndexer {

	/**
	 * @var array
	 */
	private $fields;

	/**
	 * @param array $fields
	 */
	public function __construct( array $fields ) {
		$this->fields = $fields;
	}

	/**
	 * @param EntityDocument $entity
	 * @oaram Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$availableFields = $this->getAvailableFields( $entity );

		foreach ( $this->fields as $fieldName => $field ) {
			if ( $field->hasFieldData( $entity ) ) {
				$data = $field->getFieldData( $entity );
				$document->set( $fieldName, $data );
			}
		}
	}

	private function getAvailableFields( EntityDocument $entity ) {
		if ( $entity instanceof FingerprintHolder ) {
			return array( 'labels', 'descriptions' );
		}

		return array();
	}

}
