<?php

class NextShout_ControllerAdmin_Base extends XenForo_ControllerAdmin_Abstract {

	protected function _preDispatch($action) {
		$this->assertAdminPermission('nextshout');
	}

	protected function _getObjectOrError($model, $id) {
		$method = 'get' . $model . 'ById';
		$info = $this->getModelFromCache('NextShout_Model_' . $model)->$method($id);

		if ( !$info ) {
			throw $this->responseException($this->responseError(
				new XenForo_Phrase('nextshout_requested_' . strtolower($model) . '_not_found'), 404));
		}
		return $info;
	}
}

