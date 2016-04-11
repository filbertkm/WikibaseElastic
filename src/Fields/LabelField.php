<?php

namespace Wikibase\Elastic\Fields;

use Wikibase\DataModel\Entity\EntityDocument;

class LabelField implements Field {

	/**
	 * @var string
	 */
	private $languageCode;

	/**
	 * @param string $languageCode
	 */
	public function __construct( $languageCode ) {
		$this->languageCode = $languageCode;
	}

	/**
	 * @return string
	 */
	public function getFieldName() {
		return 'label_' . $this->languageCode;
	}

	/**
	 * @see Field::getPropertyDefinition
	 *
	 * @return array
	 */
	public function getPropertyDefinition() {
		return array(
			'type' => 'string',
			'copy_to' => array( 'all', 'all_near_match' )
		);
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return boolean
	 */
	public function hasFieldData( EntityDocument $entity ) {
		return $entity->getFingerprint()->hasLabel( $this->languageCode );
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return array
	 */
	public function getFieldData( EntityDocument $entity ) {
		$terms = $entity->getFingerprint();

		return $terms->getLabel( $this->languageCode )->getText();
	}

}
