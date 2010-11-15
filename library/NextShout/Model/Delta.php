<?php

class NextShout_Model_Delta extends XenForo_Model {

	static protected $_types = array(
		'editedTitle',
		'editedAnnouncement',
		'addedShout',
		'editedShout',
		'deletedShout',
	);

	public function addDelta($deltaType, $data) {
		if ( !in_array($deltaType, self::$_types) ) {
			throw new XenForo_Exception(new XenForo_Phrase('nextshout_invalid_delta_type'));
		}

		if ( !is_scalar($data) ) {
			$data = serialize($data);
		}

		$this->_getDb()->insert('nextshout_deltas', array(
			'delta_time' => XenForo_Application::$time,
			'delta_type' => $deltaType,
			'delta_data' => $data,
		));
	}

	public function getDeltas() {
		//
	}
}

