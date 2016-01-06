<?php

namespace Wikibase\Elastic\Fields;

use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\Item;

class SiteLinkCountField implements Field {

	/**
	 * @return array
	 */
	public function getMapping() {
		return array(
			'type' => 'long'
		);
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
