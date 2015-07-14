<?php
namespace VID\UniversalContentLists\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Wolfgang Becker <wolfgang.becker@visionate.com>, Visionate Interactive Media OHG
 *  			
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

/**
 * Test case for class VID\UniversalContentLists\Controller\UniversalContentController.
 *
 * @author Wolfgang Becker <wolfgang.becker@visionate.com>
 */
class UniversalContentControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \VID\UniversalContentLists\Controller\UniversalContentController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('VID\\UniversalContentLists\\Controller\\UniversalContentController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllUniversalContentsFromRepositoryAndAssignsThemToView() {

		$allUniversalContents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$universalContentRepository = $this->getMock('VID\\UniversalContentLists\\Domain\\Repository\\UniversalContentRepository', array('findAll'), array(), '', FALSE);
		$universalContentRepository->expects($this->once())->method('findAll')->will($this->returnValue($allUniversalContents));
		$this->inject($this->subject, 'universalContentRepository', $universalContentRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('universalContents', $allUniversalContents);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenUniversalContentToView() {
		$universalContent = new \VID\UniversalContentLists\Domain\Model\UniversalContent();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('universalContent', $universalContent);

		$this->subject->showAction($universalContent);
	}
}
