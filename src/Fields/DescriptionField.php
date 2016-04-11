<?php

namespace Wikibase\Elastic\Fields;

use Wikibase\DataModel\Entity\EntityDocument;

class DescriptionField implements Field {

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
		return 'description_' . $this->languageCode;
	}

	/**
	 * @see Field::getPropertyDefinition
	 *
	 * @return array
	 */
	public function getPropertyDefinition() {
		return array(
			'type' => 'string',
			'copy_to' => array( 'all' )
		);
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return boolean
	 */
	public function hasFieldData( EntityDocument $entity ) {
		return $entity->getFingerprint()->hasDescription( $this->languageCode );
	}

	/**
	 * @param EntityDocument $entity
	 *
	 * @return array
	 */
	public function getFieldData( EntityDocument $entity ) {
		$terms = $entity->getFingerprint();

		return $terms->getDescription( $this->languageCode )->getText();
	}

}
