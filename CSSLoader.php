<?php
/**
 * CSS Loader extension.
 *
 * @file
 * @ingroup Extensions
 * @author Ephraim Gregor [http://www.ephraimgregor.com]
 * @copyright © 2015
 * @license MIT
 */

if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

$wgExtensionMessagesFiles['CSSLoader'] = __DIR__ . '/CSSLoader.i18n.php';

class CSSLoader {
	var $loaded = false;
	var $url    = null;

	public function load( $parser ) {
		if ( $this->loaded ) {
			return;
		}

		if ( ! $parser->getTitle()->mUrlform ) {
			return;
		}

		$file = $parser->getTitle()->mUrlform;
		$path = __DIR__ . '/css/' . $file . '.css';

		if ( ! file_exists( $path ) ) {
			return;
		}

		global $wgScriptPath;
		$url = wfExpandUrl( "{$wgScriptPath}/extensions/CSSLoader/css/{$file}.css" );

		$this->url = $url;
		$this->loaded = true;
	}


	public function display( $out ) {
		if ( ! $this->url ) {
			return;
		}

		// Loads page specific URL.
		$out->addExtensionStyle( $this->url );

		// Loads LiveReload.
		$script = 'document.write(\'<script src="http://\' + (location.host || \'localhost\').split(\':\')[0] + \':35729/livereload.js?snipver=1"></\' + \'script>\')';
		$out->addInlineScript( $script );
	}

}

$cssLoader = new CSSLoader;

$wgHooks['ParserClearState'][]  = array( $cssLoader, 'load' );
$wgHooks['BeforePageDisplay'][] = array( $cssLoader, 'display' );
