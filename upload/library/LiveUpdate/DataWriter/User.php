<?php

class LiveUpdate_DataWriter_User extends XFCP_LiveUpdate_DataWriter_User
{
	protected function _getFields()
	{
		$parent = parent::_getFields();

		$parent['xf_user_option']['liveupdate_display_option'] = array(
			'type' => self::TYPE_STRING, 'default' => 'tab_icon'
		);

		return $parent;
	}
}