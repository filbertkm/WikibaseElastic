<?php

namespace Wikibase\Elastic\Fields;

use Wikibase\DataModel\Entity\EntityDocument;

/**
 * Each field is intended to be by CirrusSearch as an
 * additional property of a page.
 *
 * The data returned by the field must match the field
 * type defined in the mapping. (e.g. nested must be array,
 * integer field must get an int, etc)
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
interface Field {

	/**
	 * @return array The property definition for the field, such as the field
	 *               type (e.g. "string", "long", "nested") and other attributes
	 *               like "not_analyzed".
	 *
	 *               For detailed documentation about mapping of fields, see:
	 *               https://www.elastic.co/guide/en/elasticsearch/guide/current/mapping-intro.html
	 */
	public function getPropertyDefinition();

}
