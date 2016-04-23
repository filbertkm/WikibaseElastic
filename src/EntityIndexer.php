<?php

namespace Wikibase\Elastic;

use Elastica\Document;
use Elastica\Type;
use Wikibase\DataModel\Entity\EntityDocument;

class EntityIndexer {

	/**
	 * @var EntityFieldsIndexer
	 */
	private $entityFieldsIndexer;

	/**
	 * @var Type
	 */
	private $type;

	/**
	 * @param EntityFieldsIndexer $entityFieldsIndexer
	 * @param Type $type
	 */
	public function __construct(
		EntityFieldsIndexer $entityFieldsIndexer,
		Type $type
	) {
		$this->entityFieldsIndexer = $entityFieldsIndexer;
		$this->type = $type;
	}

	/**
	 * @param EntityDocument $entity
	 * @oaram Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$this->entityFieldsIndexer->doIndex( $entity, $document );

		// @todo upsert
		$this->type->addDocument( $document );
	}

}
