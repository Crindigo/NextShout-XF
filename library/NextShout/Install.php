<?php

class NextShout_Install {

	static public function install($existingAddOn, $addOnData) {
		if ( !$existingAddOn ) {
			self::installFresh();
		} else {
			self::upgrade($existingAddOn['version_id']);
		}
	}

	static protected function installFresh() {
		$installData = new NextShout_Install_Data_Mysql();
		$tables = $installData->getTables();

		$db = XenForo_Application::get('db');
		foreach ( $tables as $tableName => $createSql ) {
			$db->query($createSql);
		}
	}

	static protected function upgrade($fromVersion) {
		$versions = self::getUpgradeVersions($fromVersion);

		foreach ( $versions as $version ) {
			$class = 'NextShout_Install_Upgrade_' . $version;
			$upgrade = new $class();
			$upgrade->upgrade();
		}
	}

	static protected function getUpgradeVersions($fromVersion) {
		$root = XenForo_Application::getInstance()->getRootDir();
		$upgradeDir = $root . '/library/NextShout/Install/Upgrade';

		$upgrades = array();
		foreach ( glob($upgradeDir . '/*.php') as $file ) {
			$versionId = intval($file);
			if ( !$versionId ) {
				continue;
			}
			$upgrades[] = $versionId;
		}

		sort($upgrades, SORT_NUMERIC);

		foreach ( $upgrades as $i => $upgrade ) {
			if ( $upgrade > $fromVersion ) {
				return array_slice($upgrades, $i);
			}
		}
		return array();
	}

	static public function getUpgradeData($version, $type = 'Mysql') {
		$class = 'NextShout_Install_Upgrade_' . $version . '_' . $type;
		return new $class();
	}

	static public function uninstall($addOnData) {
		$installData = new NextShout_Install_Data_Mysql();
		$tables = $installData->getTables();
		
		$db = XenForo_Application::get('db');
		foreach ( array_keys($tables) as $table ) {
			$db->query('DROP TABLE ' . $table);
		}
	}
}

