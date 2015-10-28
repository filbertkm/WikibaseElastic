<?php

if ( function_exists( 'wfLoadExtension' ) ) {
	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		require_once __DIR__ . '/vendor/autoload.php';
	}

	wfLoadExtension( 'WikibaseElastic' );
} else {
	die( 'WikibaseElastic requires MediaWiki 1.25+' );
}
