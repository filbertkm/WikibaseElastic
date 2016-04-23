<?php

namespace Wikibase\Elastic\FieldDefinitions;

use Wikibase\Elastic\Fields\Field;

interface FieldDefinitions {

	/**
	 * @return Field[]
	 */
	public function getFields();

}
