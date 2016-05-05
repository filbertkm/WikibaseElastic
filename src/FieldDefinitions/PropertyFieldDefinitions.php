<?php

namespace Wikibase\Elastic\FieldDefinitions;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;

class PropertyFieldDefinitions implements FieldDefinitions {

	/**
	 * @var LabelsProviderFieldDefinitions
	 */
	private $labelsProviderFieldDefinitions;

	/**
	 * @var DescriptionsProviderFieldDefinitions
	 */
	private $descriptionsProviderFieldDefinitions;

	/**
	 * @var string[]
	 */
	private $languageCodes;

	/**
	 * @param string[] $languageCodes
	 */
	public function __construct( array $languageCodes ) {
		$this->labelsProviderFieldDefinitions = new LabelsProviderFieldDefinitions( $languageCodes );
		$this->descriptionsProviderFieldDefinitions = new DescriptionsProviderFieldDefinitions( $languageCodes );

		$this->languageCodes = $languageCodes;
	}

	/**
	 * @return array
	 */
	public function getMappingProperties() {
		$labelsProviderFieldDefinitions = new LabelsProviderFieldDefinitions( $this->languageCodes );
		$descriptionsProviderFieldDefinitions = new DescriptionsProviderFieldDefinitions( $this->languageCodes );

		$properties = [];

		foreach ( $labelsProviderFieldDefinitions->getMappingProperties() as $key => $property ) {
			$properties[$key] = $property;
		}

		foreach ( $descriptionsProviderFieldDefinitions->getMappingProperties() as $key => $property ) {
			$properties[$key] = $property;
		}

		$properties['statement_count'] = [ 'type' => 'integer' ];

		return $properties;
	}

	/**
	 * @param EntityDocument $entity
	 * @param Document $document
	 */
	public function doIndex( EntityDocument $entity, Document $document ) {
		$this->labelsProviderFieldDefinitions->doIndex( $entity, $document );
		$this->descriptionsProviderFieldDefinitions->doIndex( $entity, $document );

		$document->set( 'statement_count', $entity->getStatements()->count() );
	}

}
