<?php

class NextShout_ControllerAdmin_Command extends NextShout_ControllerAdmin_Base {

	public function actionIndex() {

	}

	public function actionAdd() {

	}

	public function actionEdit() {

	}

	protected function _getAddEditResponse($viewParams) {

	}

	public function actionSave() {

	}

	public function actionDelete() {

	}

	public function actionSync() {

	}

	/**
	 * @return NextShout_Model_Command
	 */
	protected function _getCommandModel() {
		return $this->getModelFromCache('NextShout_Model_Command');
	}

	/**
	 * @return NextShout_Model_Command
	 */
	protected function _getCommandOrError($commandId) {
		return $this->_getObjectOrError('Command', $commandId);
	}
}

