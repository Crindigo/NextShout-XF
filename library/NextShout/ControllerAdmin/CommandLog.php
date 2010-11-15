<?php

class NextShout_ControllerAdmin_CommandLog extends NextShout_ControllerAdmin_Base {

	public function actionIndex() {

	}

	public function actionBrowse() {

	}

	/**
	 * @return NextShout_Model_Command
	 */
	protected function _getCommandModel() {
		return $this->getModelFromCache('NextShout_Model_Command');
	}
}

