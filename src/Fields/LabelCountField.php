<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Term\FingerprintProvider;

/**
 * @license GPL-2.0+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class LabelCountField implements Field {

	/**
	 * @see Field::getPropertyDefinition
	 *
	 * @return array
	 */
	public function getPropertyDefinition() {
		return [
			'type' => 'integer'
		];
	}

	/**
	 * @param EntityDocument $entity
	 * @param Document $doc
	 */
	public function doIndex( EntityDocument $entity, Document $doc ) {
		$doc->set( 'label_count', $this->getCount( $entity ) );
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return int
	 */
	private function getCount( EntityDocument $entity ) {
		if ( $entity instanceof FingerprintProvider ) {
			return $entity->getFingerprint()->getLabels()->count();
		}

		return 0;
	}


}
