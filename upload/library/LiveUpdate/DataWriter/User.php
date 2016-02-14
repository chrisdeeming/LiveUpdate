<?php

class LiveUpdate_DataWriter_User extends XFCP_LiveUpdate_DataWriter_User
{
	protected function _getFields()
	{
		$parent = parent::_getFields();

		$defaultOptions = XenForo_Application::getOptions()->liveUpdateDefaultOptions;

		if ($defaultOptions && is_array($defaultOptions))
		{
			$default = json_encode(array_keys($defaultOptions));
		}
		else
		{
			$default = '';
		}

		$parent['xf_user_option']['liveupdate_display_option'] = array(
			'default' => $default,
			'type' => self::TYPE_JSON
		);

		return $parent;
	}
}