<?php

namespace BlueSpice;

use RuntimeException;
use TemplateParser;

class LoadPlaceholderRegistry extends ExtensionAttributeBasedRegistry {

	public function __construct() {
		parent::__construct( "BlueSpiceFoundationLoadPlaceholders" );
	}

	/**
	 * Gets all available LoadPlaceholder template names
	 *
	 * @return string[]
	 */
	public function getAvailablePlaceholders() {
		return $this->getAllKeys();
	}

	/**
	 * Gets the path to the mustache template
	 *
	 * @param string $template
	 * @return string|null if template is not registered
	 */
	public function getPlaceholderPath( $template ) {
		$registry = $this->extensionRegistry->getAttribute( $this->attribName );
		if ( isset( $registry[$template] ) ) {
			return $registry[$template];
		}
		return null;
	}

	/**
	 * Parse given template
	 *
	 * @param string $template
	 * @param array $args
	 * @return string
	 */
	public function getParsedTemplate( $template, $args = [] ) {
		$path = $this->getPlaceholderPath( $template );
		if ( !$path ) {
			return '';
		}
		$templateParser = new TemplateParser( $path );

		try {
			return $templateParser->processTemplate( $template, $args );
		} catch ( RuntimeException $ex ) {
			return '';
		}
	}
}
