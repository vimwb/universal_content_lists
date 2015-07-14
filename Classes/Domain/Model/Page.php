<?php
namespace VID\UniversalContentLists\Domain\Model;


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
 * Page
 */
class Page extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * uid
	 * @var int
	 * @validate NotEmpty
	 */
	protected $uid;
	/**
	 * pid
	 * @var int
	 * @validate NotEmpty
	 */
	protected $pid;
	/**
	 * sorting
	 * @var int
	 * @validate NotEmpty
	 */
	protected $sorting;
	/**
	 * title
	 * @var string
	 *
	 */
	protected $title;
	/**
	 * subtitle
	 * @var string
	 *
	 */
	protected $subtitle;

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
	 * Returns the pid
	 *
	 * @return int $pid
	 */
	public function getPid() {
		return $this->pid;
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
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}
	/**
	 * Returns the subtitle
	 *
	 * @return string $subtitle
	 */
	public function getSubtitle() {
		return $this->subtitle;
	}

}