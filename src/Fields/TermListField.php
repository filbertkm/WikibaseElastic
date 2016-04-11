<?php

namespace Wikibase\Elastic\Fields;

use Elastica\Document;
use Wikibase\DataModel\Term\TermList;

class TermListField implements Field {

	/**
	 * @var string[]
	 */
	private $languageCodes;

	/**
	 * @var string[]
	 */
	private $copyToFields;

	/**
	 * @var string
	 */
	private $fieldPrefix;

	/**
	 * @param string[] $languageCodes
	 * @param string[] $copyToFields
	 * @param string $fieldPrefix
	 */
	public function __construct( array $languageCodes, array $copyToFields, $fieldPrefix ) {
		$this->languageCodes = $languageCodes;
		$this->copyToFields = $copyToFields;
		$this->fieldPrefix = $fieldPrefix;
	}

	public function getProperties() {
		$properties = array();

		foreach ( $this->languageCodes as $languageCode ) {
			$key = $this->getFieldPrefix() . $languageCode;
			$properties[$key] = $this->getPropertyDefinition();
		}

		return $properties;
	}

	public function doIndex( TermList $terms, Document $doc ) {
		foreach ( $this->languageCodes as $languageCode ) {
			if ( $terms->hasTermForLanguage( $languageCode ) ) {
				$key = $this->getFieldPrefix() . $languageCode;
				$doc->set( $key, $terms->getByLanguage( $languageCode )->getText() );
			}
		}
	}

	/**
	 * @return string
	 */
	private function getFieldPrefix() {
		return $this->fieldPrefix . '_';
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

}
