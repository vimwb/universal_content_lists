<?php

/***************
 * Add Content Elements to List
 */
$backupCTypeItems = $GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'];
$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] = array(
	array(
		'LLL:EXT:universal_content_lists/Resources/Private/Language/Backend.xlf:content_element_tab.universal_content',
		'--div--'
	),
	array(
		'LLL:EXT:universal_content_lists/Resources/Private/Language/Backend.xlf:content_element.article',
		'universal_content_article',
		'i/tt_content_textpic.gif'
	),
	array(
		'LLL:EXT:universal_content_lists/Resources/Private/Language/Backend.xlf:content_element.banner',
		'universal_content_banner',
		'i/tt_content_image.gif'
	),
);
foreach ($backupCTypeItems as $key => $value) {
	$GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'][] = $value;
}
unset($key);
unset($value);
unset($backupCTypeItems);


/***************
 * Add FlexForm for the universal_content_articlelist
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:universal_content_lists/Configuration/FlexForms/Articlelist.xml', 'universal_content_articlelist');

/***************
 * Modify the tt_content TCA
 */
$tca = array(
	'ctrl' => array(
		'requestUpdate' => $TCA['tt_content']['ctrl']['requestUpdate'] . ',colPos',
		'typeicons' => array(
			'universal_content_article' => 'tt_content_textpic.gif',
			'universal_content_banner' => 'tt_content_image.gif',
		),
	),
	'palettes' => array(
		'universal_content_relations' => array(
			'canNotCollapse' => 1,
			'showitem' => '
				tx_universalcontentlists_tags,
				--linebreak--,
				tx_universalcontentlists_related,
				--linebreak--,
				categories
			'
		),
		'universal_content_globaldisplay' => array(
			'canNotCollapse' => 1,
			'showitem' => '
				tx_universalcontentlists_pages,
				--linebreak--,
				tx_universalcontentlists_recursive,
				--linebreak--,
				tx_universalcontentlists_excludepages,
				--linebreak--,
				tx_universalcontentlists_exclude_recursive
			'
		),
	),

	'types' => array(
		'universal_content_article' => array(
			'showitem' => '
				date,
				header;LLL:EXT:cms/locallang_ttc.xlf:header_formlabel,
				tx_universalcontentlists_short;LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:short;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],
				bodytext;LLL:EXT:cms/locallang_ttc.xlf:bodytext_formlabel;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],
				rte_enabled;LLL:EXT:cms/locallang_ttc.xlf:rte_enabled_formlabel,
				image,
				tx_universalcontentlists_video,
				--div--;LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:tabs.displayRelations,
				--palette--;;universal_content_relations,
				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.extended,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
			'
		),
		'universal_content_banner' => array(
			'showitem' => '
				header,
				header_layout,
				header_link,
				image,
				tx_universalcontentlists_video,
				--div--;LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:tabs.displayRelations,
				--palette--;;universal_content_relations,
				--div--;LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:tabs.globaldisplay,
				--palette--;;universal_content_globaldisplay,
				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;access,
				--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.extended,
				--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
			'
		),
	),
	'columns' => array(

		'tx_universalcontentlists_short' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:short',
			'config' => array(
				'type' => 'text',
				'cols' => 30,
				'rows' => 2,
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts_css]'
		),

		'tx_universalcontentlists_video' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:video',
			'config' => array(
				"type" => "input",
				"size" => "30",
				"eval" => "trim",
			),
			'defaultExtras' => 'richtext:rte_transform[flag=rte_enabled|mode=ts_css]'
		),

		'tx_universalcontentlists_pages' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:display.pages',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 3,
				'minitems' => 0,
				'maxitems' => 100,
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
						'pages' => array(
							'maxItemsInResultList' => 5,
						),
					),
				),
			),
		),

		'tx_universalcontentlists_recursive' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:display.recursive',
			'config' => Array (
				'type' => 'check',
				'default' => '0'
			)
		),

		'tx_universalcontentlists_excludepages' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:display.excludepages',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 3,
				'minitems' => 0,
				'maxitems' => 100,
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
						'pages' => array(
							'maxItemsInResultList' => 5,
						),
					),
				),
			)
		),
		'tx_universalcontentlists_exclude_recursive' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:display.exclude_recursive',
			'config' => Array (
				'type' => 'check',
				'default' => '0'
			)
		),

		'tx_universalcontentlists_tags' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:tags',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tx_universalcontentlists_domain_model_tag',
				'size' => 6,
				'minitems' => 0,
				'maxitems' => 100,
				'filter' => array (
					array(
						'userFunc' => 'VID\UniversalContentLists\Backend\LanguageFilter->doFilter',
						'parameters' => array(
							'table' => 'tx_universalcontentlists_domain_model_tag',
							'allowedlanguageUIDS' => array('0','-1'),
						),
					),
				),
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
						'tx_universalcontentlists_domain_model_tag' => array(
							'maxItemsInResultList' => 5,
						),
					),
				),
			),
		),

		'tx_universalcontentlists_related' => Array (
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:related',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tt_content',
				'size' => 3,
				'minitems' => 0,
				'maxitems' => 100,
				'filter' => array (
					array(
						'userFunc' => 'VID\UniversalContentLists\Backend\LanguageFilter->doFilter',
						'parameters' => array(
							'table' => 'tt_content',
							'allowedCTypes' => array('universal_content_article'),
							'allowedlanguageUIDS' => array('0','-1'),
						),
					),
				),
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
						'tt_content' => array(
							'maxItemsInResultList' => 5,
						),
					),
				),
			),
		),

	),
);
$GLOBALS['TCA']['tt_content'] = array_replace_recursive($GLOBALS['TCA']['tt_content'], $tca);

#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--palette--;LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:palette.display;universal_content_relations', '', 'before:categories');
