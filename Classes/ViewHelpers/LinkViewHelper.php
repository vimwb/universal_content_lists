<?php
namespace VID\UniversalContentLists\ViewHelpers;

/**
 * Class LinkViewHelper
 *
 * @package Vendor\Myext\ViewHelpers
 */
class LinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('target', 'string', 'Target of link', FALSE);
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document', FALSE);
	}

	/**
	 * @param string $typoLink the link from the Link Wizzard
	 * @return string Rendered URI
	 */
	public function render($typoLink) {

		/** @var $contentObject \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
		$contentObject = $GLOBALS['TSFE']->cObj;

		$uri = $contentObject->getTypoLink_URL($typoLink);

		if (strlen($uri)) {
			$this->tag->addAttribute('href', $uri);
			$this->tag->setContent($this->renderChildren());
			$result = $this->tag->render();
		} else {
			$result = $this->renderChildren();
		}
		return $result;
	}

}