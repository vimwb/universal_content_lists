<?php
namespace VID\UniversalContentLists\Backend\ItemsProcFuncs;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Jo Hasenau <info@cybercraft.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class ColPosList  {

	/**
	 * ItemProcFunc for colpos items
	 *
	 * @param    array $params : The array of parameters that is used to render the item list
	 *
	 * @return    void
	 */
	public function itemsProcFunc(&$params) {

		#$params['items'] = $this->addColPosListLayoutItems($params['items'], $params['row']['CType']);

		if ($params['row']['pid'] > 0) {
			$params['items'] = $this->addColPosListLayoutItems($params['row']['pid'], $params['items'], $params['row']['CType']);
		} else {
			// negative uid_pid values indicate that the element has been inserted after an existing element
			// so there is no pid to get the backendLayout for and we have to get that first
			$existingElement = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('pid, CType', 'tt_content', 'uid=' . -((int)$params['row']['pid']));
			if ($existingElement['pid'] > 0) {
				$params['items'] = $this->addColPosListLayoutItems($existingElement['pid'], $params['items'], $existingElement['CType']);
			}
		}
	}

	/**
	 * Adds items to a colpos list
	 *
	 * @param   array   $items  : The array of items before the action
	 * @param   string  $CType  : The content type of the item holding the colPosList
	 *
	 * @return  array   $items: The ready made array of items
	 */
	protected function addColPosListLayoutItems($pid, array $items, $CType = '') {

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("id"=>$pid));

		if ( count($items) == 0 )
		{
			$items = $GLOBALS['TCA']['tt_content']['columns']['colPos']['config']['items'];

			array_unshift($items, array(
				'',
				'',
				NULL,
				NULL
			));

			$count = 0;
			foreach($items as &$item)
			{
				$item[0] = $GLOBALS['LANG']->sL($item[0]);
			}
		}

		$pageTS = BackendUtility::getPagesTSconfig($pid);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("pageTS"=>$pageTS));

		$colPos = $pageTS['plugin.']['tx_universalcontentlists.']['articlelistColPos'];

		$items[] = array(
			$GLOBALS['LANG']->sL('LLL:EXT:universal_content_lists/Resources/Private/Language/Backend.xlf:articles'),
			$colPos,
			NULL,
			NULL
		);

		return $items;
	}
}
