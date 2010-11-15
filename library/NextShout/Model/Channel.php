<?php

class NextShout_Model_Channel extends XenForo_Model {

	public function getChannelById($id) {
		$channel = $this->_getDb()->fetchRow('
			SELECT *
			FROM nextshout_channels
			WHERE channel_id = ?
		', $id);
		
		if ( $channel ) {
			return $this->_standardizeValues($channel);
		}
		return false;
	}

	public function getChannelByIdentifier($identifier) {
		$channel = $this->_getDb()->fetchRow('
			SELECT *
			FROM nextshout_channels
			WHERE channel_ident = ?
		', $identifier);

		if ( $channel ) {
			return $this->_standardizeValues($channel);
		}
		return false;
	}

	public function getAllChannels() {
		$channels = $this->_getDb()->fetchAllKeyed('
			SELECT *
			FROM nextshout_channels
			ORDER BY channel_name
		', 'channel_id');
		return $this->_standardizeValuesMulti($channels);
	}

	public function getAllChannelTitles() {
		return $this->_getDb()->fetchAll('
			SELECT channel_id, channel_name, channel_ident
			FROM nextshout_channels
			ORDER BY channel_name
		');
	}

	public function getChannelCounts() {
		$channels = $this->getAllChannelTitles();
		$shoutModel = $this->getModelFromCache('NextShout_Model_Shout');
		foreach ( $channels as &$channel ) {
			$channel['num_shouts'] = $shoutModel->getShoutCountForChannel($channel['channel_id']); 
		}
		return $channels;

		/*
		// didn't handle zero counts... and yes i know this query is broke.
		return $this->_getDb()->fetchAll('
			SELECT s.shout_channel AS channel_id, c.channel_name, COUNT(*) AS num_shouts
			FROM nextshout_channels c
			LEFT JOIN nextshout_shouts s ON (s.shout_channel = c.channel_id)
			GROUP BY s.shout_channel
			ORDER BY c.channel_name
		');
		*/
	}

	public function getBlankChannel() {
		return array(
			'channel_id'      => 0,
			'channel_ident'   => '',
			'channel_name'    => '',
			'channel_color'   => '',
			'channel_anyone'  => 0,
			'channel_groups'  => array(),
			'channel_include' => array(),
			'channel_exclude' => array(),
		);
	}

	/**
	 * Unserializes the groups, include, and exclude keys.
	 * 
	 * @param array $channel Channel data array.
	 * @return array
	 */
	protected function _standardizeValues($channel) {
		$channel['channel_groups']  = unserialize($channel['channel_groups']);
		$channel['channel_include'] = unserialize($channel['channel_include']);
		$channel['channel_exclude'] = unserialize($channel['channel_exclude']);
		return $channel;
	}

	protected function _standardizeValuesMulti($channels) {
		foreach ( $channels as &$channel ) {
			$channel = $this->_standardizeValues($channel);
		}
		return $channels;
	}
}

