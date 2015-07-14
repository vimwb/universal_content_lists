<?php

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl'] = array(
	'_DEFAULT' => array(
		'fixedPostVars' => array(
			'_DEFAULT' => array(
				array(
					'GETvar' => 'tx_universalcontentlists_detailview[universalContent]',
					'optional' => TRUE,
					'lookUpTable' => array(
						'table' => 'tt_content',
						'id_field' => 'uid',
						'alias_field' => 'header',
						'addWhereClause' => ' AND NOT deleted',
						'useUniqueCache' => 1,
						'useUniqueCache_conf' => array(
							'strtolower' => 1,
							'spaceCharacter' => '-',
						),
						'languageGetVar' => 'L',
						'languageExceptionUids' => '',
						'languageField' => 'sys_language_uid',
						'transOrigPointerField' => 'l18n_parent',
						'autoUpdate' => 1,
						'expireDays' => 180,
					),
				),

				array(
					'GETvar' => 'tx_universalcontentlists_detailview[action]',
					'valueMap' => array(
						'details' => 'show',
					),
					'noMatch' => 'bypass',
				),

			),
		),
		'postVarSets' => array(
			'_DEFAULT' => array(

			),
		),
	)
);

/**
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars']['_DEFAULT'][] = array(
'GETvar' => 'tx_universalcontentlists_detailview[universalContent]',
'optional' => TRUE,
'lookUpTable' => array(
'table' => 'tt_content',
'id_field' => 'uid',
'alias_field' => 'header',
'addWhereClause' => ' AND NOT deleted',
'useUniqueCache' => 1,
'useUniqueCache_conf' => array(
'strtolower' => 1,
'spaceCharacter' => '-',
),
'languageGetVar' => 'L',
'languageExceptionUids' => '',
'languageField' => 'sys_language_uid',
'transOrigPointerField' => 'l18n_parent',
'autoUpdate' => 1,
'expireDays' => 180,
),
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars']['_DEFAULT'][] = array(
'GETvar' => 'tx_universalcontentlists_detailview[action]',
'valueMap' => array(
'details' => 'show',
),
'noMatch' => 'bypass',
);
 */
