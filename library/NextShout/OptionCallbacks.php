<?php

class NextShout_OptionCallbacks {
	
	// used for banned/gagged user list
	static public function normalizeNumberList(&$optionValue, $dw, $optionId) {
		$optionValue = trim($optionValue);
		if ( empty($optionValue) ) {
			return true;
		}

		// replace any sequence of non-numbers with a single space
		$optionValue = preg_replace('#[^0-9]+#', ' ', $optionValue);
		return true;
	}

	// creates an updated_title delta 
	static public function updateTitle(&$optionValue, $dw, $optionId) {
		$oldTitle = XenForo_Application::get('options')->nextshout_title;
		if ( $oldTitle != $optionValue ) {
			$deltaModel = XenForo_Model::create('NextShout_Model_Delta');
			$deltaModel->addDelta('editedTitle', $optionValue);
		}
		return true;
	}

	static public function updateAnnouncement(&$optionValue, $dw, $optionId) {
		$oldAnnouncement = XenForo_Application::get('options')->nextshout_announcement;
		if ( $oldAnnouncement != $optionValue ) {
			$deltaModel = XenForo_Model::create('NextShout_Model_Delta');
			$deltaModel->addDelta('editedAnnouncement', $optionValue);
		}
		return true;
	}
}

