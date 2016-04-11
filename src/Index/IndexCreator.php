<?php

namespace Wikibase\Elastic\Index;

use Elastica\Index;

class IndexCreator {

	public function create( Index $index ) {
		$index->create( [ // args
			'number_of_shards' => 2,
			'number_of_replicas' => 1,
			'analysis' => [
				'analyzer' => [
					'indexAnalyzer' => [
						'type' => 'standard'
					],
					'searchAnalyzer' => [
						'type' => 'standard'
					]
				]
			],
			false // don't recreate index
		] );
	}

}
