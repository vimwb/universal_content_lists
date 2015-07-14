<?php
namespace VID\UniversalContentLists\ViewHelpers;

class IfTrimmedlengthViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * renders <f:then> child if $condition is true, otherwise renders <f:else> child.
	 *
	 * @param String $condition View helper condition
	 * @return string the rendered string
	 * @api
	 */
	public function render($condition) {

		$condition = strip_tags($condition);
		$condition = htmlspecialchars_decode($condition);
		$condition = trim($condition);

		#\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump(array("condition"=>"|".$condition."|"));

		if (strlen($condition) > 0) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}
