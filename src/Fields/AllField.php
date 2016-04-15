<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;

class AllField implements Field {

	/**
	 * @see Field::getPropertyDefinition
	 *
	 * @return array
	 */
	public function getPropertyDefinition() {
		return [
			'type' => 'string'
		];
	}

	public function doIndex( EntityDocument $entity, Document $doc ) {
		// no-op
	}

}
