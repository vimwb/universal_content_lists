<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_universalcontentlists_domain_model_tag'] = array(
	'ctrl' => $TCA['tx_universalcontentlists_domain_model_tag']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,headline,taglist_pid'
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'items' => array(
					'1' => array(
						'0' => 'LLL:EXT:cms/locallang_ttc.xlf:hidden.I.0'
					)
				)
			)
		),
		'starttime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => '13',
				'max' => '20',
				'eval' => 'datetime',
				'default' => '0'
			),
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly'
		),
		'endtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => '13',
				'max' => '20',
				'eval' => 'datetime',
				'default' => '0',
				'range' => array(
					'upper' => mktime(0, 0, 0, 12, 31, 2020)
				)
			),
			'l10n_mode' => 'exclude',
			'l10n_display' => 'defaultAsReadonly'
		),
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
						-1
					),
					array(
						'LLL:EXT:lang/locallang_general.xlf:LGL.default_value',
						0
					)
				)
			)
		),
		'l10n_parent' => Array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array(
				'type' => 'select',
				'items' => Array(
					Array('', 0),
				),
				'foreign_table' => 'tx_bootstrappackage_accordion_item',
				'foreign_table_where' => 'AND tx_bootstrappackage_accordion_item.uid=###REC_FIELD_l10n_parent### AND tx_bootstrappackage_accordion_item.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => Array(
			'config' => array(
				'type' => 'passthrough'
			)
		),

		'taglist_pid' => Array (
			'exclude' => 0,
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:taglist_pid',
			'l10n_mode' => 'exclude',
			'config' => Array (
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
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

		'headline' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:headline',
			'config' => array(
				'type' => 'input',
				'size' => 50,
				'eval' => 'trim,required'
			),
		),
	),
	'palettes' => array(

	),
	'types' => array(
		'1' => array(
			'showitem' =>
				'--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
				headline,taglist_pid,
				--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access,
				--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility'
		),
	),
);
