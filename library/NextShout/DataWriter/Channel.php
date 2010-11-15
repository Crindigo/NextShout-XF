<?php

class NextShout_DataWriter_Channel extends XenForo_DataWriter {
	
	protected $_existingDataErrorPhrase = 'nextshout_requested_channel_not_found';

	protected function _getFields() {
		return array(
			'nextshout_channels' => array(
				'channel_id' => array(
					'type'          => self::TYPE_UINT,
					'autoIncrement' => true
				),
				'channel_ident'    => array(
					'type'          => self::TYPE_STRING,
					'required'      => true,
					'maxLength'     => 16,
					'verification'  => array('$this', '_verifyChannelIdent'),
					'requiredError' => 'nextshout_please_enter_valid_channel_identifier',
				),
				'channel_name' => array(
					'type'          => self::TYPE_STRING,
					'required'      => true,
					'maxLength'     => 32,
					'requiredError' => 'nextshout_please_enter_valid_channel_name',
				),
				'channel_color' => array(
					'type'    => self::TYPE_STRING,
					'default' => '',
				),
				'channel_anyone' => array(
					'type'    => self::TYPE_BOOLEAN,
					'default' => 0,
				),
				'channel_groups' => array(
					'type'    => self::TYPE_SERIALIZED,
					'default' => 'a:0:{}',
				),
				'channel_include' => array(
					'type'    => self::TYPE_SERIALIZED,
					'default' => 'a:0:{}',
				),
				'channel_exclude' => array(
					'type'    => self::TYPE_SERIALIZED,
					'default' => 'a:0:{}',
				),
			),
		);
	}

	protected function _getExistingData($data) {
		if ( !$id = $this->_getExistingPrimaryKey($data) ) {
			return false;
		}
		return array('nextshout_channels' => $this->_getChannelModel()->getChannelById($id));
	}

	protected function _getUpdateCondition($tableName) {
		return 'channel_id = ' . $this->_db->quote($this->getExisting('channel_id'));
	}

	protected function _verifyChannelIdent($ident) {
		if ( preg_match('#[^a-zA-Z0-9_]#', $ident) ) {
			$this->error(new XenForo_Phrase('nextshout_please_enter_an_ident_using_only_alphanumeric'));
			return false;
		}

		if ( $this->isInsert() || $ident != $this->getExisting('channel_ident') ) {
			$existing = $this->_getChannelModel()->getChannelByIdentifier($ident);
			if ( $existing ) {
				$this->error(new XenForo_Phrase('nextshout_channel_identifiers_must_be_unique'));
				return false;
			}
		}

		return true;
	}

	/**
	 * @return NextShout_Model_Channel
	 */
	protected function _getChannelModel() {
		return $this->getModelFromCache('NextShout_Model_Channel');
	}
}

