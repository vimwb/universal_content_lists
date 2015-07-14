<?php
namespace VID\UniversalContentLists\Domain\Repository;


	/***************************************************************
	 *
	 *  Copyright notice
	 *
	 *  (c) 2015 Wolfgang Becker <wolfgang.becker@visionate.com>, Visionate Interactive Media OHG
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
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

/**
 * The repository for UniversalContents
 */
class UniversalContentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @var \TYPO3\CMS\Core\Database\QueryGenerator $queryGenerator
	 */
	protected $queryGenerator = NULL;

	/**
	 * Returns all objects of this repository.
	 *
	 * @return QueryResultInterface|array
	 */
	public function findAllWithoutStoragePage() {

		$query = $this->createQuery();

		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		return $query->execute();
	}

	/**
	 * Returns a filtered List
	 *
	 * @param \TYPO3\Flow\Cache\Frontend\AbstractFrontend $cacheInstance
	 * @param array $forbiddenCTypes
	 * @param array $pidList
	 * @param array $categoriesIDList
	 * @param array $ColPosIDList
	 * @param int $limit
	 * @param int $offset
	 * @param int $tagID
	 * @param string $sortfield
	 * @param string $sorting
	 * @parem bool $countQuery
	 *
	 * @return QueryResultInterface|array|int
	 */
	public function getListWithQuerySettings($cacheInstance,
											 array $forbiddenCTypes,
											 array $pidList,
											 array $categoriesIDList,
											 array $ColPosIDList,
											 $limit,
											 $offset = 0,
											 $tagID = 0,
											 $sortfield="sorting",
											 $sorting=\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
											 $countQuery=FALSE,
											 $searchWord=NULL) {

		$query = $this->createQuery();

/*	$query->getQuerySettings()
			->setRespectStoragePage(FALSE)
			->setRespectSysLanguage(FALSE)
			->setLanguageMode("ignore")
			->setLanguageOverlayMode("hideNonTranslated");*/

		$query->getQuerySettings()
			->setRespectStoragePage(FALSE);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("getListWithQuerySettings"=>array( "forbiddenCTypes"=>$forbiddenCTypes,  "pidList"=>$pidList,  "categoriesIDList"=>$categoriesIDList,  "ColPosIDList"=>$ColPosIDList, "limit"=>$limit, "offset"=>$offset)));

		$constraints = array();

		if (count($forbiddenCTypes) > 0) {

			$forbiddenContraints = array();
			foreach($forbiddenCTypes as $ctype)
			{
				$forbiddenContraints[] = $query->logicalNot($query->equals("ctype",$ctype));
			}
			if ( count($forbiddenContraints) )
			{
				$constraints[] = $query->logicalAnd($forbiddenContraints);
			}
		}

		if (count($pidList) == 1) {
			$constraints[] = $query->equals("pid", $pidList[0]);
		}
		else if (count($pidList) > 1) {
			$constraints[] = $query->in("pid", $pidList);
		}

		if (count($categoriesIDList) > 0) {

			$catIDContraints = array();
			foreach($categoriesIDList as $catID)
			{
				$catIDContraints[] = $query->contains("categories", $catID);
			}
			if ( count($catIDContraints) )
			{
				$constraints[] = $query->logicalOr($catIDContraints);
			}
		}

		if (count($ColPosIDList) > 0) {
			$constraints[] = $query->in("colPos", $ColPosIDList);
		}

		if ($tagID > 0) {
			$constraints[] = $query->contains("tags", $tagID);
		}

		if($GLOBALS['TSFE']->sys_language_uid > 0) {
			$constraints[] = $query->in('sys_language_uid', array(-1, $GLOBALS['TSFE']->sys_language_uid));
			/*
						$query->getQuerySettings()
								->setRespectSysLanguage(TRUE)
								->setLanguageUid($language);*/
		}

		if ( $searchWord != NULL){

			$searchWord = $GLOBALS['TYPO3_DB']->quoteStr($searchWord, "tt_content");

			$constraints[] = $query->logicalOr(
				$query->like('header', "%$searchWord%", false),
				$query->like('short', "%$searchWord%", false),
				$query->like('bodytext', "%$searchWord%", false),
				$query->like('imagecaption', "%$searchWord%", false)
			);
		}

		if ( count($constraints) > 0 )
		{
			$query->matching($query->logicalAnd($constraints));
		}

		$sortingArray = array($sortfield => $sorting);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sortingArray);

		$cacheIdentifier = hash("md5", implode(";",$forbiddenCTypes).implode(";",$pidList).implode(";",$categoriesIDList).implode(";",$ColPosIDList).$tagID."L:".$GLOBALS['TSFE']->sys_language_uid."SW:".$searchWord);

		if ($GLOBALS["TSFE"]->no_cache == false && $cacheInstance->has($cacheIdentifier)) {
			$res = $cacheInstance->get($cacheIdentifier);
			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($cacheIdentifier, "res from cache: ".$cacheIdentifier);
		}
		else
		{
			$res = $query
				->setOrderings($sortingArray)
				->execute();

			// safe un limited list to cache
			$cacheInstance->set($cacheIdentifier, $res);
			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($cacheIdentifier, "res TO cache".$cacheIdentifier);
		}

		// filter records that are not on Pages that match showOnPages
		$res = $this->filterResult($res->toArray());

		if ( $countQuery == FALSE ){
			// limit
			$res = array_splice($res, $offset, $limit);
		}
		else
		{
			$res = count($res);
		}

			/** @var Typo3DbQueryParser $queryParser */
			#$queryParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Storage\\Typo3DbQueryParser');
			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->parseQuery($query));


		return $res;
	}

	/**
	 * Filters records by showOnPages and excludeOnPages;
	 *
	 * @param array $resArray
	 *
	 * @return array
	 */
	private function filterResult($resArray)
	{
		$pid = $GLOBALS['TSFE']->id;
		$this->queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Database\\QueryGenerator' );

		/**
		 * @var \VID\UniversalContentLists\Domain\Model\UniversalContent $universalContent
		 */
		foreach($resArray as $key => $universalContent)
		{
			// show on this Pages Only

			$showOnPagesUIDArray = array();

			$showOnpages = array();

			if (is_object($universalContent->getShowOnPages()) )
			{
				$showOnpages = $universalContent->getShowOnPages()->toArray();
			}


			if ( count($showOnpages) ){

				/**
				 * @var \VID\UniversalContentLists\Domain\Model\Page $page
				 */
				foreach($showOnpages as $page)
				{
					if ( $universalContent->getShowOnPagesRecursive() ){
						$showOnPagesUIDArray = array_merge($showOnPagesUIDArray, $this->getRecursivePageUIDs($page->getUid()));
					}
					else{
						$showOnPagesUIDArray[] = $page->getUid();
					}
				}

				if ( count($showOnPagesUIDArray) ){
					#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("showOnPagesUIDArray" => $showOnPagesUIDArray, "pid"=>$pid));

					if ( !in_array( $pid, $showOnPagesUIDArray )){
						unset($resArray[$key]);
					}
				}
			}

			// exclude on this Pages

			$excludeOnPagesUIDArray = array();

			$excludeOnPages = array();

			if (is_object($universalContent->getExcludeOnPages()) )
			{
				$excludeOnPages = $universalContent->getExcludeOnPages()->toArray();
			}

			if ( count($excludeOnPages) ){

				/**
				 * @var \VID\UniversalContentLists\Domain\Model\Page $page
				 */
				foreach($excludeOnPages as $page)
				{
					if ( $universalContent->getExcludeOnPagesRecursive() ){
						$excludeOnPagesUIDArray = array_merge($excludeOnPagesUIDArray, $this->getRecursivePageUIDs($page->getUid()));
					}
					else{
						$excludeOnPagesUIDArray[] = $page->getUid();
					}
				}

				if ( count($excludeOnPagesUIDArray) ){
					#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("excludeOnPagesUIDArray" => $excludeOnPagesUIDArray, "pid"=>$pid));

					if ( in_array( $pid, $excludeOnPagesUIDArray )){
						unset($resArray[$key]);
					}
				}
			}
		}

		return $resArray;
	}




	/**
	 * Return an Array of PIDs recursive to given PID
	 *
	 * @param int $pageUID
	 *
	 * @return array
	 */
	private function getRecursivePageUIDs($pageUID)
	{
		$depth = 999999;
		$rGetTreeList = $this->queryGenerator->getTreeList($pageUID, $depth, 0, 1); //Will be a string
		return explode(',',$rGetTreeList);
	}

	/**
	 * Returns a List by Tags
	 *
	 * @param \VID\UniversalContentLists\Domain\Model\UniversalContent $universalContent
	 * @param array $tagIDs
	 * @param int $limit
	 * @param string $sortfield
	 * @param string $sorting
	 *
	 * @return QueryResultInterface|array|int
	 */
	public function getRelatedByTagList($universalContent,
										$tagIDs,
										$limit,
										$sortfield="sorting",
										$sorting=\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING) {

		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("getListWithQuerySettings"=>array( "forbiddenCTypes"=>$forbiddenCTypes,  "pidList"=>$pidList,  "categoriesIDList"=>$categoriesIDList,  "ColPosIDList"=>$ColPosIDList, "limit"=>$limit, "offset"=>$offset)));

		$constraints = array();

		foreach($tagIDs as $tagUID){
			$constraints[] = $query->contains("tags", $tagUID);
		}

		$constraints[] = $query->logicalNot(
			$query->equals(
				"uid", $universalContent->getUid()
			)
		);

		$query->matching($query->logicalAnd($constraints));

		$sortingArray = array($sortfield => $sorting);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($sortingArray);

		$res = $query
			->setLimit($limit)
			->setOrderings($sortingArray)
			->execute();

		/** @var Typo3DbQueryParser $queryParser */
		#$queryParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Storage\\Typo3DbQueryParser');
		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->parseQuery($query));

		return $res;
	}

}