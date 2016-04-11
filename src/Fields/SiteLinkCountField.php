<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;

class SiteLinkCountField implements Field {

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

	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return mixed
	 */
	public function getFieldData( EntityDocument $entity ) {
		if ( $entity instanceof Item ) {
			return $entity->getSiteLinkList()->count();
		}

		return 0;
	}

}
