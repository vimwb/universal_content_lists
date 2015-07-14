<?php
namespace VID\UniversalContentLists\Backend;

class LanguageFilter  {

	/**
	 * doFilter
	 * filters tag for language UIDs in allowedlanguageUIDS
	 * siehe : http://docs.typo3.org/typo3cms/TCAReference/Reference/Columns/Group/Index.html#type
	 *
	 * @param array $parameters
	 * @param $parentObject
	 * @return array
	 */
	public function doFilter(array $parameters, $parentObject) {

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("parameters"=>$parameters, "parentObject"=>$parentObject));

		$fieldValues = array();

		foreach( $parameters['values'] as $item )
		{
			$itemArray = explode("_", $item);
			$itemUID = (int)$itemArray[count($itemArray)-1];

			$where = 'uid=' . $itemUID;

			if ( $parameters['allowedCTypes'] != null && count($parameters['allowedCTypes']) > 0  )
			{
				foreach( $parameters['allowedCTypes'] as $cType ){
					$where .= " AND CType = '$cType' ";
				}
			}

			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("where"=>$where));

			#exec_SELECTgetSingleRow ($select_fields, $from_table, $where_clause, $groupBy= '', $orderBy= '', $numIndex=FALSE)
			$dbItem = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('uid, sys_language_uid', $parameters['table'], $where);

			if ( !in_array($dbItem['sys_language_uid'], $parameters['allowedlanguageUIDS']) ){
				#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("Ignoredtag"=>$tag));
			}
			else
			{
				$fieldValues[] = $item;
			}
		}

		return $fieldValues;
	}

}