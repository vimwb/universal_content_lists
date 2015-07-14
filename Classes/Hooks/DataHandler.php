<?php
namespace VID\UniversalContentLists\Hooks;

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

/**
 * Class/Function which offers TCE main hook functions.
 *
 * @author         Jo Hasenau <info@cybercraft.de>
 * @package        TYPO3
 * @subpackage     tx_gridelements
 */
class DataHandler {

	/**
	 * Function to set the colPos -99 of an element with tx_universalcontentlists_tt_content
	 * changes are applied to the field array of the parent object by reference
	 *
	 * @param    array                                    $fieldArray : The array of fields and values that have been saved to the datamap
	 * @param    string                                   $table      : The name of the table the data should be saved to
	 * @param    int                                      $id         : The uid of the page we are currently working on
	 * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $parentObj  : The parent object that triggered this hook
	 *
	 * @return void
	 */
/*
	public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObj) {

		if ($table === 'tt_content' && $fieldArray['CType'] != "universal_content_articlelist" && $fieldArray['tx_universalcontentlists_tt_content'] > 0)
		{
			# print_r (array("preProcessFieldArray id"=>$id, "fieldArray"=>$fieldArray));
			// set filedsarray colPos
			$fieldArray["colPos"] = -99;
		}
	}
*/
	/**
	 * Function to set the colPos -99 of an element with tx_universalcontentlists_tt_content
	 *
	 * @param    string                                   $status
	 * @param    string                                   $table      : The name of the table the data should be saved to
	 * @param    int                                      $id         : The uid of the page we are currently working on
	 * @param    array                                    $fieldArray : The array of fields and values that have been saved to the datamap
	 * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $parentObj  : The parent object that triggered this hook
	 *
	 * @return void
	 */
	/*
	public function processDatamap_afterDatabaseOperations(&$status, &$table, &$id, &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObj) {

		if ($table === 'tt_content')
		{
			#print_r (array("afterDatabaseOperations status"=>$status,"id"=>$id, "fieldArray"=>$fieldArray));

			// case editieren tt_content aus dem list oder page modul mit ref tx_universalcontentlists_tt_content auf universal_content_articlelist
			if ( $fieldArray["tx_universalcontentlists_tt_content"] > 0  )
			{
				// alle elemente mit tx_universalcontentlists_tt_content > 0 bearbeiten
				// t3lib_DB.exec_UPDATEquery ($table,$where, $fields_values, $no_Quote_fiels=FALSE)

				$updateFields = array(
					'colPos' => -99,
				);

				if(!is_numeric($id)) {
					$id = $parentObj->substNEWwithIDs[$id];
				}

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid = '.$id, $updateFields, FALSE);
			}

			// case Inline edit of universal_content_articlelist ended
			if ( $fieldArray["tx_universalcontentlists_articlelist_item"] > 0 )
			{
				// alle elemente mit tx_universalcontentlists_tt_content > 0 bearbeiten
				// t3lib_DB.exec_UPDATEquery ($table,$where, $fields_values, $no_Quote_fiels=FALSE)

				$updateFields = array(
					'colPos' => -99,
				);

				if(!is_numeric($id)) {
					$id = $parentObj->substNEWwithIDs[$id];
				}

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'tx_universalcontentlists_tt_content = '.$id, $updateFields, FALSE);
			}

	}
}*/

	/**
	 * Function to set the cType to universal_content_article if colPos matches articlelistColPos
	 *
	 * @param    \TYPO3\CMS\Core\DataHandling\DataHandler $pObj  : The parent object that triggered this hook
	 *
	 * @return void
	 */
	public function processDatamap_afterAllOperations(\TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("pObj"=>$pObj->datamap));

		$datamap_tt_content = $pObj->datamap['tt_content'];

		if ( count($datamap_tt_content) > 0 )
		{
			foreach( $datamap_tt_content as $id => $record)
			{
				if(!is_numeric($id)) {
					$id = $pObj->substNEWwithIDs[$id];
				}

				$existingElement = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('uid, pid, CType, colPos', 'tt_content', 'uid=' . $id);
				#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("id"=>$id, "existingElement" => $existingElement));

				// getPageTS
				$pageTS = BackendUtility::getPagesTSconfig($existingElement["pid"]);
				#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("pageTS"=>$pageTS));

				$colPos = $pageTS['plugin.']['tx_universalcontentlists.']['articlelistColPos'];
				$forceCtype = $pageTS['plugin.']['tx_universalcontentlists.']['forceCtypeInarticlelistColPos'];

				if ( $forceCtype == 1 )
				{
					if ( $existingElement["colPos"] == $colPos)
					{
						// set colPos // CType
						// TODO: add TS option to select type to be forced
						$updateFields = array(
							'CType' => 'universal_content_article'
						);

						$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid = '.$id, $updateFields, FALSE);
					}
				}
			}

			unset($datamap_tt_content);
		}
	}

}
