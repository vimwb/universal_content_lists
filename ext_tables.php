<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
/***************
 * Backend Module
 */
if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		$_EXTKEY,
		'tools',	 // Make module a submodule of 'tools'
		'migratonscript',	// Submodule key
		'',						// Position
		array(
			'Admin' => 'migrateData, doMigrateData',

		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/Backend.xlf',
		)
	); */

}

/***************
 * Make the extension configuration accessible
 */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}
###

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Contentlist',
	'Content List'
);

$pluginSignature = str_replace('_','',$_EXTKEY) . '_contentlist';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

###

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'DetailView',
	'Detail View'
);

$pluginSignature_detailview = str_replace('_','',$_EXTKEY) . '_detailview';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature_detailview] = 'pi_flexform';

/***************
 * flexForm
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_contentlist.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature_detailview, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_detailview.xml');


/***************
 *static TS
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Universal Content Lists');


$TCA['tx_universalcontentlists_domain_model_tag'] = array(
	'ctrl' => array(
		'label' => 'headline',
		#'sortby' => 'label',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'title' => 'LLL:EXT:universal_content_lists/Resources/Private/Language/locallang_db.xlf:tag',
		'delete' => 'deleted',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'hideAtCopy' => FALSE,
		'prependAtCopy' => 'LLL:EXT:lang/locallang_general.xlf:LGL.prependAtCopy',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'languageField' => 'sys_language_uid',
		'dividers2tabs' => TRUE,
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tx_universalcontentlists_domain_model_tag.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('universal_content_lists') . 'Resources/Public/Icons/tx_universalcontentlists_domain_model_universalcontent.gif',
	),
);

/***************
 * Allow Tags on Standart Pages
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_universalcontentlists_domain_model_tag');

/***************
 * itemsProcFunc for colPos
 */
$TCA['tt_content']['columns']['colPos']['config']['itemsProcFunc'] = 'VID\UniversalContentLists\Backend\ItemsProcFuncs\ColPosList->itemsProcFunc';

/***************
 * Reset extConf array to avoid errors
 */
if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}