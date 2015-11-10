<?php
namespace VID\UniversalContentLists\Controller;


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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ContentListController
 */
class ContentListController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * universalContentRepository
	 *
	 * @var \VID\UniversalContentLists\Domain\Repository\UniversalContentRepository
	 * @inject
	 */
	protected $universalContentRepository = NULL;

	/**
	 * pageRepository
	 *
	 * @var \VID\UniversalContentLists\Domain\Repository\PageRepository
	 * @inject
	 */
	protected $pageRepository = NULL;

	/**
	 * tagRepository
	 *
	 * @var \VID\UniversalContentLists\Domain\Repository\TagRepository
	 * @inject
	 */
	protected $tagRepository = NULL;


	/**
	 * @var \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend
	 */
	protected $cacheInstance;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initializeCache();
	}

	/**
	 * Initialize cache instance to be ready to use
	 *
	 * @return void
	 */
	protected function initializeCache() {
		//\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
		try {
			$this->cacheInstance = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('tx_universalcontentlists_cache');
		} catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $e) {
			$this->cacheInstance = $GLOBALS['typo3CacheFactory']->create(
				'tx_universalcontentlists_cache',
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache']['frontend'],
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache']['backend'],
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_universalcontentlists_cache']['options']
			);
		}
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		$tagID = 0;

		if ( $this->settings["isTagList"] == "1" && $this->request->hasArgument('tagID'))
		{
			$tagID = $this->request->getArgument('tagID');
			/**
			 * @var \VID\UniversalContentLists\Domain\Model\Tag $tag
			 */
			$tag = $this->tagRepository->findByUid($tagID);

			$this->view->assign('isTagList', "1");
			$this->view->assign('tag', $tag);
		}

		#http://www.ska-keller.de/index.php?id=50&tx_universalcontentlists_contentlist[searchWord]=Geister

		$searchWord = NULL;
		if ( $this->settings["selectMode"]=="searchresults" ){
			if ($this->request->hasArgument('searchWord') ){
				$searchWord = $this->request->getArgument('searchWord');
			}

			if ( strlen($searchWord) == 0 ){
				return;
			}
		}



		$currentPage = 1;

		if ( $this->settings['usePaging'] == '1'){

			if ( $this->request->hasArgument('currentPage') )
			{
				$currentPage = (int)$this->request->getArgument('currentPage');
			}

			$numResults = $this->getList($tagID, $currentPage, TRUE, $searchWord);

			$numPages = ceil((int)$numResults / $this->settings['itemsPerPage']);

			if ( $numPages > 1 )
			{
				$pages = array();
				$pageNumber = 1;

				while($pageNumber < $numPages+1){
					$pages[] = array(
						"pagenumber" => $pageNumber,
						"isCurrentPage"=>($pageNumber==$currentPage),
						"pageClass"=>($pageNumber==$currentPage)?"active":""
					);
					$pageNumber++;
				}

				// limit pages
				$limitedPages = array();
				$limitToPages = $this->settings['numPages'];
				$startAtPage = $currentPage - floor($limitToPages/2);

				if ( $startAtPage < floor($limitToPages/2) )
				{
					$startAtPage = 1;
				}

				$endPage = $startAtPage + ($limitToPages-1);
				if ( $endPage > count($pages) )
				{
					$endPage = $numPages;
				}

				// use $limitToPages even if $endPages is LastPage
				if ( (($endPage - $startAtPage) < $limitToPages) && (($endPage - ($limitToPages-1)) > 0 ) )
				{
					$startAtPage = $endPage - ($limitToPages-1);
				}

				if ( $startAtPage > 1 )
				{
					$limitedPages[] = array(
						"pagenumber" => "1",
						"isCurrentPage"=>false,
						"pageClass"=>""
					);
					$limitedPages[] = array(
						"pagenumber" => "...",
						"isCurrentPage"=>false,
						"pageClass"=>"disabled"
					);
				}

				// show only 5 Pages
				for ($i = $startAtPage-1; $i < count($pages); $i++)
				{
					if ( $i<$endPage )
					{
						$limitedPages[] = $pages[$i];
					}
				}

				if ( $endPage < $numPages)
				{

					$limitedPages[] = array(
						"pagenumber" => "...",
						"isCurrentPage"=>false,
						"pageClass"=>"disabled"
					);

					$limitedPages[] = array(
						"pagenumber" => $numPages,
						"isCurrentPage"=>false,
						"pageClass"=>""
					);
				}

				$this->view->assign('pages', $limitedPages);

				$pagingvars['numArticles'] = (int)$numResults;
				$pagingvars['numPages'] = $numPages;
				$pagingvars['currentPage'] = $currentPage;

				$pagingvars['isFirstPage'] = ($currentPage == 1);
				$pagingvars['isLastPage'] = ($currentPage == $numPages);

				$pagingvars['prevPage'] = ($currentPage==1)?1:($currentPage - 1);
				$pagingvars['nextPage'] = ($currentPage==$numPages)?$numPages:($currentPage + 1);

				$pagingvars['prevClass'] = ($currentPage == 1)?"disabled":"";
				$pagingvars['nextClass'] = ($currentPage == $numPages)?"disabled":"";

				$this->view->assign('pagination', $pagingvars);
			}

			$this->view->assign('usePaging', $this->settings['usePaging']);

		}

		$this->view->assign('numArticles', (int)$numResults);

		$this->view->assign('searchlistPID',  $this->settings['searchlistPID']);

		$this->view->assign('currentPage', $currentPage);

		$res = $this->getList($tagID, $currentPage, FALSE, $searchWord);

		$this->view->assign("sysLanguageUid", $GLOBALS['TSFE']->sys_language_uid);

		$this->view->assign('articles', $res);

		$this->view->assign('isSearchList', $this->settings["selectMode"]=="searchresults");
		$this->view->assign('searchWord', $searchWord);


		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("vars"=>$this->settings["variables"]));

		$this->view->assign('listImageMaxWidth', $this->settings["variables"]["listImageMaxWidth"]);

		$this->view->assign('biggerwidth', $this->settings["variables"]["biggerwidth"] <= $this->settings["variables"]["listImageMaxWidth"] ? $this->settings["variables"]["biggerwidth"] : $this->settings["variables"]["listImageMaxWidth"]);
		$this->view->assign('largewidth', $this->settings["variables"]["largewidth"] <= $this->settings["variables"]["listImageMaxWidth"] ? $this->settings["variables"]["largewidth"] : $this->settings["variables"]["listImageMaxWidth"]);
		$this->view->assign('mediumwidth', $this->settings["variables"]["mediumwidth"] <= $this->settings["variables"]["listImageMaxWidth"] ? $this->settings["variables"]["mediumwidth"] : $this->settings["variables"]["listImageMaxWidth"]);
		$this->view->assign('smallwidth', $this->settings["variables"]["smallwidth"] <= $this->settings["variables"]["listImageMaxWidth"] ? $this->settings["variables"]["smallwidth"] : $this->settings["variables"]["listImageMaxWidth"]);

		$this->view->assign('biggerheight', $this->settings["variables"]["biggerheight"] <= $this->settings["variables"]["listImageMaxHeight"] ? $this->settings["variables"]["biggerheight"] : $this->settings["variables"]["listImageMaxHeight"]);
		$this->view->assign('largeheight', $this->settings["variables"]["largeheight"] <= $this->settings["variables"]["listImageMaxHeight"] ? $this->settings["variables"]["largeheight"] : $this->settings["variables"]["listImageMaxHeight"]);
		$this->view->assign('mediumheight', $this->settings["variables"]["mediumheight"] <= $this->settings["variables"]["listImageMaxHeight"] ? $this->settings["variables"]["mediumheight"] : $this->settings["variables"]["listImageMaxHeight"]);
		$this->view->assign('smallheight', $this->settings["variables"]["smallheight"] <= $this->settings["variables"]["listImageMaxHeight"] ? $this->settings["variables"]["smallheight"] : $this->settings["variables"]["listImageMaxHeight"]);

		$this->view->assign('showImageMaxWidth', $this->settings["variables"]["showImageMaxWidth"]);
		$this->view->assign('showImageMaxHeight', $this->settings["variables"]["showImageMaxHeight"]);
	}


	/**
	 * action getList
	 *
	 * @param \VID\UniversalContentLists\Domain\Model\Tag $tag
	 * @return QueryResultInterface|array|int
	 */
	public function getList($tagID = 0, $currentPage = 1, $countQuery = FALSE, $searchWord = NULL)
	{
			// forbiddenCTypes
			$forbiddenCTypes = array();
			if ( isset($this->settings["forbiddenCTypes"]) && $this->settings["forbiddenCTypes"] != '' )
			{
				$forbiddenCTypes = explode( ',', $this->settings["forbiddenCTypes"] );
			}

			// pids
			$pidList = array();
			switch( $this->settings["selectMode"] )
			{
				case "pid":
					$pidList = explode( ',', $this->settings["storagePIDs"] );
					break;

				case "currentPage":
					$pidList = array($GLOBALS['TSFE']->id);
					break;
			}

			// categories
			$categoriesIDList = array();
			if ( isset($this->settings["limitToCategories"]) && $this->settings["limitToCategories"] != '' )
			{
				$categoriesIDList = explode( ',', $this->settings["limitToCategories"] );
			}

			// columns
			$ColPosIDList = array();
			if ( isset($this->settings["limitTocColPosIDs"]) && $this->settings["limitTocColPosIDs"] != '' )
			{
				$ColPosIDList = explode( ',', $this->settings["limitTocColPosIDs"] );
			}

			// limit
			$limit = (int)$this->settings["itemsPerPage"];

			$offset = 0;
			if ( $currentPage > 1 )
			{
				$offset = $limit*($currentPage-1);
			}

			// sortfield
			$validSortfields = array("sorting", "colPos" ,"date", "crdate", "tstamp");
			$sortfield_ = $this->settings["sortfield"];
			$sortfield = (in_array($sortfield_, $validSortfields))?$sortfield_:"sorting";

			// sorting
			$validSortings = array("ASC", "DESC");
			$sorting_ = $this->settings["sorting"];
			$sorting = (in_array($sorting_, $validSortings))?$sorting_:"ASC";

			return $this->universalContentRepository->getListWithQuerySettings($this->cacheInstance, $forbiddenCTypes, $pidList, $categoriesIDList, $ColPosIDList, $limit, $offset, $tagID, $sortfield, $sorting, $countQuery, $searchWord);
	}


}