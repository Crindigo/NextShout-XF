<?php

class NextShout_Model_Shout extends XenForo_Model {
	
	public function getOrphanShoutCount() {
		return (int) $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM nextshout_shouts
			WHERE shout_channel NOT IN (
				SELECT channel_id
				FROM nextshout_channels
			)
		');
	}

	public function getShoutCount() {
		return (int) $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM nextshout_shouts
		');
	}

	public function getShoutCountForChannel($channelId) {
		return (int) $this->_getDb()->fetchOne('
			SELECT COUNT(*)
			FROM nextshout_shouts
			WHERE shout_channel = ?
		', $channelId);
	}
}

