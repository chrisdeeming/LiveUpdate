<?php

class LiveUpdate_Listener
{
	public static function extendAccountController($class, array &$extend)
	{
		$extend[] = 'LiveUpdate_ControllerPublic_Account';
	}

	public static function extendUserDataWriter($class, array &$extend)
	{
		$extend[] = 'LiveUpdate_DataWriter_User';
	}

	public static function initDependencies(XenForo_Dependencies_Abstract $dependencies, array $data)
	{
		XenForo_Template_Helper_Core::$helperCallbacks['liveupdateoptions'] = array(
			'LiveUpdate_Listener', 'helperLiveUpdateOptions'
		);
	}

	public static function helperLiveUpdateOptions($json = false)
	{
		$visitor = XenForo_Visitor::getInstance();
		if (!$visitor->user_id)
		{
			return array();
		}

		if (!empty($visitor['permissions']['general']['liveUpdateChangeOptions']))
		{
			$output = json_decode($visitor->liveupdate_display_option, true);
		}
		else
		{
			$output = array_keys(XenForo_Application::getOptions()->liveUpdateDefaultOptions);
		}

		return ($json ? json_encode($output) : $output);
	}
}