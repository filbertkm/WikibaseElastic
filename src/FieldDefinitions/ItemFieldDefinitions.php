<?php

namespace Wikibase\Elastic\FieldDefinitions;

use Elastica\Document;
use Wikibase\DataModel\Entity\EntityDocument;

class ItemFieldDefinitions implements FieldDefinitions {

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
		$properties = [];

		foreach ( $this->labelsProviderFieldDefinitions->getMappingProperties() as $key => $property ) {
			$properties[$key] = $property;
		}

		foreach ( $this->descriptionsProviderFieldDefinitions->getMappingProperties() as $key => $property ) {
			$properties[$key] = $property;
		}

		$properties['sitelink_count'] = [ 'type' => 'integer' ];
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

		$document->set( 'sitelink_count', $entity->getSiteLinkList()->count() );
		$document->set( 'statement_count', $entity->getStatements()->count() );
	}

}
