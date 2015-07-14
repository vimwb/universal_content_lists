<?php
namespace VID\UniversalContentLists\ViewHelpers;

/***************************************************************
 *
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2014 Benjamin Kott, http://www.bk2k.info
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 *
 ***************************************************************/

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * @author Wolfgang Becker <wolfgang.becker@visiomnate.com>
 */
class VideoPlayerViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {


	/**
	 * @var string
	 */
	protected $tagName = 'video';

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();

		$this->registerTagAttribute('preload', 'string', 'Preload Video', FALSE);
		$this->registerTagAttribute('src', 'string', 'The Video Source', FALSE);
		$this->registerTagAttribute('data-setup', 'string', 'Setup Object', FALSE);
	}

	/**
	 * Renders the video tag
	 *
	 * @param string $url a path to a video
	 * @param string $width width of the video.
	 * @param string $height height of the video.
	 * @param string $type video type youtube | vimeo
	 *
	 * @return string Rendered tag
	 */
	public function render($url, $width = NULL, $height = NULL, $type = "youtube" ) {

		$this->tag->addAttribute('preload', 'auto');

		$this->tag->addAttribute('width', $width);
		$this->tag->addAttribute('height', $height);

		$this->tag->addAttribute('src', '');
		$this->tag->addAttribute('data-setup', '{ "ytcontrols": "true", "techOrder": ["'.$type.'"], "src": "'.$url.'" }');

		$this->tag->forceClosingTag(true);

		return $this->tag->render();

/*		switch ($type)
		{
			case "youtube":
				break;

			case "vimeo":
			default:
				break;
		}*/
	}

}
