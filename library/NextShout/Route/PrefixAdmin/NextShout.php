<?php

class NextShout_Route_PrefixAdmin_NextShout implements XenForo_Route_Interface {

	static protected $_subRoutes = array(
		'channel'    => 'Channel',
		'command'    => 'Command',
		'commandlog' => 'CommandLog',
		'dashboard'  => 'Dashboard',
	);

	public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router) {
		// Make nextshout/foo-bar/ go to Foo::actionBar()
		$parts = explode('/', $routePath, 2);

		if ( strpos($parts[0], '-') !== false ) {
			list($subController, $subAction) = explode('-', $parts[0]);
		} else {
			$subController = $parts[0];
			$subAction     = 'index';
		}

		if ( isset($parts[1]) && !empty($parts[1]) ) {
			$param = $parts[1];
		} else {
			$param = null;
		}

		if ( $param !== null ) {
			$this->_handleParams($request, $subController, $subAction, $param);
		}

		$controller = 'NextShout_ControllerAdmin_' . self::$_subRoutes[$subController];

		return $router->getRouteMatch($controller, $subAction, 'nextshout');
	}

	public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams) {
		$suffix = $this->_getSuffix($action, $data);

		if ( !empty($suffix) ) {
			return "$outputPrefix/$action/$suffix";
		} else {
			return "$outputPrefix/$action";
		}
	}

	protected function _handleParams($request, $controller, $action, $param) {
		if ( $controller == 'channel' ) {
			$request->setParam('channel_id', $param);
		} else if ( $controller == 'command' ) {
			$request->setParam('command_id', $param);
		}
	}

	protected function _getSuffix($action, $data) {
		$suffix = '';
		switch ( $action ) {
			case 'channel-edit':
			case 'channel-delete':
				$suffix = $data['channel_id'];
				break;
			case 'command-edit':
			case 'command-delete':
				$suffix = $data['command_id'];
				break;
		}
		return $suffix;
	}
}

