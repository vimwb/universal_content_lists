<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/***************
 * Make the extension configuration accessible
 */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'VID.' . $_EXTKEY,
	'Contentlist',
	array(
		'ContentList' => 'list',
	),
	// non-cacheable actions
	array(
		'ContentList' => '',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'VID.' . $_EXTKEY,
	'DetailView',
	array(
		'DetailView' => 'show',
	),
	// non-cacheable actions
	array(
		'DetailView' => '',
	)
);

/***************
 * PageTs
 */

// Add Content Elements to newContentElement Wizard
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTS/Mod/Wizards/newContentElement.txt">');

// add Page TS
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/PageTS/pageTS.ts">');


/***************
 * Use RealUrl Config

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')
	&& $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['UseRealUrlConfig'] == 1
) {
	@include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY, 'Configuration/RealURL/Default.php'));
}
*/
if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
	#@include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY, 'Configuration/RealURL/Default.php'));
}


/***************
 * HOOKS
 *
 * processDatamapClass
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:universal_content_lists/Classes/Hooks/DataHandler.php:VID\\UniversalContentLists\\Hooks\\DataHandler';


/***************
 * Register Cache
 */
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache'] = array();
}

if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache']['backend'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache']['backend'] = 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend';
}


/***************
 * Reset extConf array to avoid errors
 */
if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
	$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}
