<?php

class NextShout_ViewAdmin_ChannelEdit extends XenForo_ViewAdmin_Base {

	public function renderHtml() {
		$this->_params['channel']['channel_include'] = implode(' ', $this->_params['channel']['channel_include']);
		$this->_params['channel']['channel_exclude'] = implode(' ', $this->_params['channel']['channel_exclude']);
		
		foreach ( $this->_params['userGroups'] as $userGroupId => $title ) {
			$this->_params['userGroups'][$userGroupId] = array(
				'label'    => $title,
				'value'    => $userGroupId,
				'selected' => in_array($userGroupId, $this->_params['channel']['channel_groups']),
			);
		}
	}
}

