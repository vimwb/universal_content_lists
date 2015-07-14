<?php
namespace VID\UniversalContentLists\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
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
 * UniversalContent
 */
class UniversalContent extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * uid
	 * @var int
	 * @validate NotEmpty
	 */
	protected $uid;

	/**
	 * page
	 * @lazy
	 * @var \VID\UniversalContentLists\Domain\Model\Page
	 */
	protected $page;

	/**
	 * Categories
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
	 */
	protected $categories;

	/**
	 * sorting
	 * @var int
	 * @validate NotEmpty
	 */
	protected $sorting;

	/**
	 * ctype
	 * @var string
	 *
	 */
	protected $ctype;

	/**
	 * date
	 * @var DateTime
	 *
	 */
	protected $date;

	/**
	 * header
	 * @var string
	 *
	 */
	protected $header;

	/**
	 * headerLink
	 * @var string
	 *
	 */
	protected $headerLink;

	/**
	 * headerLayout
	 * @var string
	 *
	 */
	protected $headerLayout;

	/**
	 * short
	 * @var string
	 *
	 */
	protected $short;

	/**
	 * bodytext
	 * @var string
	 *
	 */
	protected $bodytext;

	/**
	 * image
	 * @var string
	 *
	 */
	protected $image;

	/**
	 * video
	 * @var string
	 *
	 */
	protected $video;

	/**
	 * imageLink
	 * @var string
	 *
	 */
	protected $imageLink;

	/**
	 * imagecaption
	 * @var string
	 *
	 */
	protected $imagecaption;

	/**
	 * colPos
	 * @var int
	 *
	 */
	protected $colPos;

	/**
	 * relations
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\UniversalContent>
	 */
	protected $relations;

	/**
	 * tags
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Tag>
	 */
	protected $tags;


	/**
	 * showOnPages
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page>
	 */
	protected $showOnPages;

	/**
	 * showOnPagesRecursive
	 * @var boolean
	 */
	protected $showOnPagesRecursive;

	/**
	 * excludeOnPages
	 * @lazy
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page>
	 */
	protected $excludeOnPages;

	/**
	 * excludeOnPagesRecursive
	 * @var boolean
	 */
	protected $excludeOnPagesRecursive;

	/**
	 * __construct
	 *
	 */
	public function __construct() {
		$this->initStorageObjects();
	}

	/**
	 * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {

		$this->categories = new ObjectStorage();
		$this->tags = new ObjectStorage();
		$this->relations = new ObjectStorage();
		$this->showOnPages = new ObjectStorage();
		$this->excludeOnPages = new ObjectStorage();
	}

	/**
	 * sets the uid
	 *
	 * @param int $uid
	 * @return void
	 */
	public function setUid($uid) {
		$this->uid = $uid;
	}

	/**
	 * returns the uid
	 *
	 * @return int $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * sets a page
	 *
	 * @param \VID\UniversalContentLists\Domain\Model\Page $page
	 * @return void
	 */
	public function setPage(\VID\UniversalContentLists\Domain\Model\Page $page) {
		$this->page = $page;
	}

	/**
	 * Returns the categories
	 *
	 * @return \VID\UniversalContentLists\Domain\Model\Page $page
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * Returns the sorting
	 *
	 * @return int $sorting
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * sets the date
	 *
	 * @param DateTime $date
	 * @return void
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * returns the date
	 *
	 * @return DateTime $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Returns the header
	 *
	 * @return string $header
	 */
	public function getHeader() {
		return $this->header;
	}
	/**
	 * Returns the headerLink
	 *
	 * @return string $headerLink
	 */
	public function getHeaderLink() {
		return $this->headerLink;
	}

	/**
	 * returns the headerLayout
	 *
	 * @return string $headerLayout
	 */
	public function getHeaderLayout() {
		return $this->headerLayout;
	}

	/**
	 * sets the headerLayout
	 *
	 * @param string $headerLayout
	 * @return void
	 */
	public function setHeaderLayout($headerLayout) {
		$this->headerLayout = $headerLayout;
	}

	/**
	 * returns the short
	 *
	 * @return string $short
	 */
	public function getShort() {
		return $this->short;
	}

	/**
	 * sets the short
	 *
	 * @param string $short
	 * @return void
	 */
	public function setShort($short) {
		$this->short = $short;
	}


	/**
	 * Returns the bodytext
	 *
	 * @return string $bodytext
	 */
	public function getBodytext() {
		return $this->bodytext;
	}

	/**
	 * Returns the image
	 *
	 * @return string $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * returns the video
	 *
	 * @return string $video
	 */
	public function getVideo() {
		return $this->video;
	}

	/**
	 * sets the video
	 *
	 * @param string $video
	 * @return void
	 */
	public function setVideo($video) {
		$this->video = $video;
	}



	/**
	 * Returns the imageLink
	 *
	 * @return string $imageLink
	 */
	public function getImageLink() {
		return $this->imageLink;
	}

	/**
	 * Returns the colPos
	 *
	 * @return int $colPos
	 */
	public function getColPos() {
		return $this->colPos;
	}

	/**
	 * sets the ctype
	 *
	 * @param string $ctype
	 * @return void
	 */
	public function setCtype($ctype) {
		$this->ctype = $ctype;
	}

	/**
	 * returns the ctype
	 *
	 * @return string $ctype
	 */
	public function getCtype() {
		return $this->ctype;
	}

	/**
	 * sets the imagecaption
	 *
	 * @param string $imagecaption
	 * @return void
	 */
	public function setImagecaption($imagecaption) {
		$this->imagecaption = $imagecaption;
	}

	/**
	 * returns the imagecaption
	 *
	 * @return string $imagecaption
	 */
	public function getImagecaption() {
		return $this->imagecaption;
	}

	/**
	 * Adds a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $category) {
		$this->categories->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(\TYPO3\CMS\Extbase\Domain\Model\Category $categoryToRemove) {
		$this->categories->detach($categoryToRemove);
	}

	/**
	 * Returns the categories
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Sets the categories
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories) {
		$this->categories = $categories;
	}

	/**
	 * sets the relations
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\UniversalContent> $relations
	 * @return void
	 */
	public function setRelations($relations) {
		$this->relations = $relations;
	}

	/**
	 * returns the relations
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\UniversalContent> $relations
	 */
	public function getRelations() {
		return $this->relations;
	}


	###############


	/**
	 * sets the tags
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Tag> $tags
	 * @return void
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}

	/**
	 * returns the tags
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Tag> $tags
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * returns the showOnPages
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page> $showOnPages
	 */
	public function getShowOnPages() {
		return $this->showOnPages;
	}

	/**
	 * sets the showOnPages
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page> $showOnPages
	 * @return void
	 */
	public function setShowOnPages($showOnPages) {
		$this->showOnPages = $showOnPages;
	}

	/**
	 * returns the showOnPagesRecursive
	 *
	 * @return boolean $showOnPagesRecursive
	 */
	public function getShowOnPagesRecursive() {
		return $this->showOnPagesRecursive;
	}

	/**
	 * sets the showOnPagesRecursive
	 *
	 * @param boolean $showOnPagesRecursive
	 * @return void
	 */
	public function setShowOnPagesRecursive($showOnPagesRecursive) {
		$this->showOnPagesRecursive = $showOnPagesRecursive;
	}

	/**
	 * returns the excludeOnPages
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page> $excludeOnPages
	 */
	public function getExcludeOnPages() {
		return $this->excludeOnPages;
	}

	/**
	 * sets the excludeOnPages
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\VID\UniversalContentLists\Domain\Model\Page> $excludeOnPages
	 * @return void
	 */
	public function setExcludeOnPages($excludeOnPages) {
		$this->excludeOnPages = $excludeOnPages;
	}

	/**
	 * returns the excludeOnPagesRecursive
	 *
	 * @return boolean $excludeOnPagesRecursive
	 */
	public function getExcludeOnPagesRecursive() {
		return $this->excludeOnPagesRecursive;
	}

	/**
	 * sets the excludeOnPagesRecursive
	 *
	 * @param boolean $excludeOnPagesRecursive
	 * @return void
	 */
	public function setExcludeOnPagesRecursive($excludeOnPagesRecursive) {
		$this->excludeOnPagesRecursive = $excludeOnPagesRecursive;
	}




}