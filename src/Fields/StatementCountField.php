<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Statement\StatementListHolder;

class StatementCountField implements Field {

	/**
	 * @see Field::getPropertyDefinition
	 *
	 * @return array
	 */
	public function getPropertyDefinition() {
		return array(
			'type' => 'long'
		);
	}

	public function doIndex( EntityDocument $entity, Document $doc ) {
		$doc->set( 'statement_count', $this->getCount( $entity ) );
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return int
	 */
	private function getCount( EntityDocument $entity ) {
		if ( $entity instanceof StatementListHolder ) {
			return $entity->getStatements()->count();
		}

		return 0;
	}

}
