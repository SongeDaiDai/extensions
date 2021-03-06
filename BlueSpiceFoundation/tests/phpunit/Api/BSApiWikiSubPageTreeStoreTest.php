<?php

namespace BlueSpice\Tests\Api;

use BlueSpice\Tests\BSApiExtJSStoreTestBase;

/**
 * @group medium
 * @group api
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceFoundation
 */
class BSApiWikiSubPageTreeStoreTest extends BSApiExtJSStoreTestBase {
	protected $iFixtureTotal = 2;
	protected $tablesUsed = [ 'page' ];

	protected function getStoreSchema() {
		return [
			'text' => [
				'type' => 'string'
			],
			'id' => [
				'type' => 'string'
			],
			'page_link' => [
				'type' => 'string'
			],
			'leaf' => [
				'type' => 'boolean'
			],
			'expanded' => [
				'type' => 'boolean'
			],
			'loaded' => [
				'type' => 'boolean'
			]
		];
	}

	protected function setUp(): void {
		parent::setUp();
		$oDbw = $this->db;
		$oDbw->insert( 'page', [
			'page_title' => "Dummy",
			'page_namespace' => 12,
			'page_restrictions' => '',
			'page_latest' => 1,
			'page_len' => 1,
			'page_random' => wfRandom(),
			'page_touched' => $oDbw->timestamp(),
			'page_is_redirect' => 0,
			'page_is_new' => 1,
		] );

		$oDbw->insert( 'page', [
			'page_title' => "Dummy/First",
			'page_namespace' => 12,
			'page_restrictions' => '',
			'page_random' => 0,
			'page_latest' => 1,
			'page_len' => 1,
			'page_random' => wfRandom(),
			'page_touched' => $oDbw->timestamp(),
			'page_is_redirect' => 0,
			'page_is_new' => 1,
		] );

		$oDbw->insert( 'page', [
			'page_title' => "Dummy/Second",
			'page_namespace' => 12,
			'page_restrictions' => '',
			'page_random' => 0,
			'page_latest' => 1,
			'page_len' => 1,
			'page_random' => wfRandom(),
			'page_touched' => $oDbw->timestamp(),
			'page_is_redirect' => 0,
			'page_is_new' => 1,
		] );
	}

	protected function createStoreFixtureData() {
		return 2;
	}

	protected function getModuleName() {
		return 'bs-wikisubpage-treestore';
	}

	public function provideSingleFilterData() {
		return [
			'Filter by text' => [ 'string', 'eq', 'text', 'First', 1 ]
		];
	}

	public function provideMultipleFilterData() {
		return [
			'Filter by text and page_link' => [
				[
					[
						'type' => 'string',
						'comparison' => 'eq',
						'field' => 'text',
						'value' => 'Second'
					],
					[
						'type' => 'string',
						'comparison' => 'ct',
						'field' => 'page_link',
						'value' => 'Dummy'
					]
				],
				1
			]
		];
	}

	protected function getAdditionalParams() {
		return [
			'node' => 'Help:Dummy'
		];
	}

	public function provideKeyItemData() {
		return [
			[ 'text', 'First' ],
			[ 'text', 'Second' ],
			[ 'id', 'Help:Dummy/First' ]
		];
	}

	protected function getResultsNodeName() {
		return 'children';
	}
}
