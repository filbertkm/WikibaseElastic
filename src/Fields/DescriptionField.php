<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;

class DescriptionField implements Field {

	/**
	 * @var string
	 */
	private $languageCode;

	/**
	 * @var string[]
	 */
	private $copyToFields;

	/**
	 * @param string $languageCode
	 * @param string[] $copyToFields
	 */
	public function __construct( $languageCode, array $copyToFields = array() ) {
		$this->languageCode = $languageCode;
		$this->copyToFields = $copyToFields;
	}

	/**
	 * @return array
	 */
	public function getPropertyDefinition() {
		return [
			'type' => 'string',
			'copy_to' => $this->copyToFields
		];
	}

	/**
	 * @param EntityDocument $entity
	 * @param Document $doc
	 */
	public function doIndex( EntityDocument $entity, Document $doc ) {
		$terms = $entity->getDescriptions();

		if ( $terms->hasTermForLanguage( $this->languageCode ) ) {
			$key = $this->getFieldName();
			$doc->set( $key, $terms->getByLanguage( $this->languageCode )->getText() );
		}
	}

	/**
	 * @return string
	 */
	public function getFieldName() {
		return 'description_' . $this->languageCode;
	}

}
