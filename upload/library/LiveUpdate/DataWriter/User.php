<?php

class LiveUpdate_DataWriter_User extends XFCP_LiveUpdate_DataWriter_User
{
	protected function _getFields()
	{
		$parent = parent::_getFields();

		$default = json_encode(array_keys(
			XenForo_Application::getOptions()->liveUpdateDefaultOptions
		));

		$parent['xf_user_option']['liveupdate_display_option'] = array(
			'default' => $default ? $default : '',
			'type' => self::TYPE_JSON
		);

		return $parent;
	}
}