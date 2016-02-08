<?php

class LiveUpdate_DataWriter_User extends XFCP_LiveUpdate_DataWriter_User
{
	protected function _getFields()
	{
		$parent = parent::_getFields();

		$parent['xf_user_option']['liveupdate_display_option'] = array(
			'default' => json_encode(array_keys(
				XenForo_Application::getOptions()->liveUpdateDefaultOptions
			)),
			'type' => self::TYPE_JSON
		);

		return $parent;
	}
}