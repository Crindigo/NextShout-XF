<?php

class NextShout_ControllerAdmin_Dashboard extends NextShout_ControllerAdmin_Base {

	public function actionIndex() {
		$channelCounts = $this->getModelFromCache('NextShout_Model_Channel')->getChannelCounts();
		$viewParams = array(
			'channelCounts' => $channelCounts,
			'totalCount'    => $this->getModelFromCache('NextShout_Model_Shout')->getShoutCount(),
			'orphanCount'   => $this->getModelFromCache('NextShout_Model_Shout')->getOrphanShoutCount(),
		);
		return $this->responseView('NextShout_ViewAdmin_Dashboard', 'nextshout_splash', $viewParams);
	}
}

