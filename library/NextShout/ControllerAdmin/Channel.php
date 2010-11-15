<?php

class NextShout_ControllerAdmin_Channel extends NextShout_ControllerAdmin_Base {

	public function actionIndex() {
		$channels = $this->_getChannelModel()->getAllChannelTitles();
		
		$viewParams = array(
			'channels' => $channels,
		);
		return $this->responseView('NextShout_ViewAdmin_ChannelList', 'nextshout_channel_list', $viewParams);
	}

	public function actionAdd() {
		$channel = $this->_getChannelModel()->getBlankChannel();
		return $this->_getAddEditResponse(array('channel' => $channel));
	}

	public function actionEdit() {
		$channelId = $this->_input->filterSingle('channel_id', XenForo_Input::UINT);
		$channel = $this->_getChannelOrError($channelId);
		return $this->_getAddEditResponse(array('channel' => $channel));
	}

	protected function _getAddEditResponse(array $viewParams) {
		$viewParams += array(
			'userGroups' => $this->getModelFromCache('XenForo_Model_UserGroup')->getAllUserGroupTitles(),
		);
		return $this->responseView('NextShout_ViewAdmin_ChannelEdit', 'nextshout_channel_edit', $viewParams);
	}

	public function actionSave() {
		$this->_assertPostOnly();

		$channelId = $this->_input->filterSingle('channel_id', XenForo_Input::UINT);
		$dwInput = $this->_input->filter(array(
			'channel_ident'   => XenForo_Input::STRING,
			'channel_name'    => XenForo_Input::STRING,
			'channel_color'   => XenForo_Input::STRING,
			'channel_anyone'  => XenForo_Input::UINT,
			'channel_groups'  => XenForo_Input::ARRAY_SIMPLE,
			'channel_include' => XenForo_Input::STRING,
			'channel_exclude' => XenForo_Input::STRING,
		));

		$dwInput['channel_include'] = preg_split('#\s+#', $dwInput['channel_include'], -1, PREG_SPLIT_NO_EMPTY);
		$dwInput['channel_exclude'] = preg_split('#\s+#', $dwInput['channel_exclude'], -1, PREG_SPLIT_NO_EMPTY);

		$dw = XenForo_DataWriter::create('NextShout_DataWriter_Channel');
		if ( $channelId ) {
			$dw->setExistingData($channelId);
		}
		$dw->bulkSet($dwInput);
		$dw->save();

		return $this->responseRedirect(
			XenForo_ControllerResponse_Redirect::SUCCESS,
			XenForo_Link::buildAdminLink('nextshout/channel')
		);
	}

	public function actionDelete() {
		if ( $this->isConfirmedPost() ) {
			return $this->_deleteData('NextShout_DataWriter_Channel', 'channel_id',
				XenForo_Link::buildAdminLink('nextshout/channel'));
		} else {
			$channelId = $this->_input->filterSingle('channel_id', XenForo_Input::UINT);
			$channel   = $this->_getChannelOrError($channelId);
			
			$viewParams = array('channel' => $channel);
			return $this->responseView('NextShout_ViewAdmin_ChannelDelete', 'nextshout_channel_delete', $viewParams);
		}
	}

	/**
	 * @return NextShout_Model_Channel
	 */
	protected function _getChannelModel() {
		return $this->getModelFromCache('NextShout_Model_Channel');
	}

	/**
	 * @return NextShout_Model_Channel
	 */
	protected function _getChannelOrError($channelId) {
		return $this->_getObjectOrError('Channel', $channelId);
	}
}

