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

/**
 * DetailViewController
 */
class DetailViewController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {


	/**
	 * universalContentRepository
	 *
	 * @var \VID\UniversalContentLists\Domain\Repository\UniversalContentRepository
	 * @inject
	 */
	protected $universalContentRepository = NULL;

	/**
	 * action show
	 *
	 * @param \VID\UniversalContentLists\Domain\Model\UniversalContent $universalContent
	 * @return void
	 */
	public function showAction(\VID\UniversalContentLists\Domain\Model\UniversalContent $universalContent) {

		$currentPage = 1;
		if ( $this->request->hasArgument('currentPage') )
		{
			$currentPage = (int)$this->request->getArgument('currentPage');
		}
		$this->view->assign('currentPage', $currentPage);

		$this->view->assign("sysLanguageUid", $GLOBALS['TSFE']->sys_language_uid);

		$this->view->assign('showImageMaxWidth', $this->settings["variables"]["showImageMaxWidth"]);
		$this->view->assign('biggerwidth', $this->settings["variables"]["biggerwidth"] <= $this->settings["variables"]["showImageMaxWidth"] ? $this->settings["variables"]["biggerwidth"] : $this->settings["variables"]["showImageMaxWidth"]);
		$this->view->assign('largewidth', $this->settings["variables"]["largewidth"] <= $this->settings["variables"]["showImageMaxWidth"] ? $this->settings["variables"]["largewidth"] : $this->settings["variables"]["showImageMaxWidth"]);
		$this->view->assign('mediumwidth', $this->settings["variables"]["mediumwidth"] <= $this->settings["variables"]["showImageMaxWidth"] ? $this->settings["variables"]["mediumwidth"] : $this->settings["variables"]["showImageMaxWidth"]);
		$this->view->assign('smallwidth', $this->settings["variables"]["smallwidth"] <= $this->settings["variables"]["showImageMaxWidth"] ? $this->settings["variables"]["smallwidth"] : $this->settings["variables"]["showImageMaxWidth"]);

		$this->view->assign('listImageMaxWidth', $this->settings["variables"]["listImageMaxWidth"]);

		$this->view->assign('showImageMaxHeight', $this->settings["variables"]["showImageMaxHeight"]);
		$this->view->assign('biggerheight', $this->settings["variables"]["biggerheight"] <= $this->settings["variables"]["showImageMaxHeight"] ? $this->settings["variables"]["biggerheight"] : $this->settings["variables"]["showImageMaxHeight"]);
		$this->view->assign('largeheight', $this->settings["variables"]["largeheight"] <= $this->settings["variables"]["showImageMaxHeight"] ? $this->settings["variables"]["largeheight"] : $this->settings["variables"]["showImageMaxHeight"]);
		$this->view->assign('mediumheight', $this->settings["variables"]["mediumheight"] <= $this->settings["variables"]["showImageMaxHeight"] ? $this->settings["variables"]["mediumheight"] : $this->settings["variables"]["showImageMaxHeight"]);
		$this->view->assign('smallheight', $this->settings["variables"]["smallheight"] <= $this->settings["variables"]["showImageMaxHeight"] ? $this->settings["variables"]["smallheight"] : $this->settings["variables"]["showImageMaxHeight"]);

		$this->view->assign('listImageMaxHeight', $this->settings["variables"]["listImageMaxHeight"]);

		$tags = $universalContent->getTags();

		$tagUIDs = array();

		/**
		 * @var \VID\UniversalContentLists\Domain\Model\Tag $tag
		 */
		foreach($tags as $tag){
			$tagUIDs[] = $tag->getUid();
		}

		if ( $this->settings["getRelatedFromTags"] == "1" && count($tagUIDs) > 0 ){

			// sortfield
			$validSortfields = array("sorting", "colPos" ,"date", "crdate", "tstamp");
			$sortfield_ = $this->settings["sortfield"];
			$sortfield = (in_array($sortfield_, $validSortfields))?$sortfield_:"sorting";

			// sorting
			$validSortings = array("ASC", "DESC");
			$sorting_ = $this->settings["sorting"];
			$sorting = (in_array($sorting_, $validSortings))?$sorting_:"ASC";

			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("tagUIDs"=>$tagUIDs));

			$limit = (int)$this->settings["relatedLimit"];

			$universalContent->setRelations(
				$this->universalContentRepository->getRelatedByTagList(
					$universalContent,
					$tagUIDs,
					$limit,
					$sortfield,
					$sorting
				)
			);

			#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("universalContent"=>$universalContent));
		}

		$this->view->assign('detailArticle', $universalContent);
	}


}