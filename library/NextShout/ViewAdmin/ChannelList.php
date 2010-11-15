<?php

/**
 * Not really necessary, just messing around and testing alternate output formats. :-p
 */
class NextShout_ViewAdmin_ChannelList extends XenForo_ViewAdmin_Base {

	public function renderJson() {
		return $this->_params['channels'];
	}

	public function renderXml() {
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->formatOutput = true;

		$channels = $doc->createElement('channels');
		$doc->appendChild($channels);

		foreach ( $this->_params['channels'] as $channel ) {
			$channelElement = $doc->createElement('channel');
			$channelElement->setAttribute('id', $channel['channel_id']);
			$channelElement->appendChild($doc->createElement('ident', $channel['channel_ident']));
			$channelElement->appendChild($doc->createElement('name', $channel['channel_name']));
			$channels->appendChild($channelElement);
		}

		return $doc->saveXML();
	}
}
