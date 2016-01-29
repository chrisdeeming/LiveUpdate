<?php

class LiveUpdate_Install
{
	protected static $_db = null;

	public static function installer($previousAddOn)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			throw new XenForo_Exception('This add-on requires XenForo 1.2.0 or higher.', true);
		}

		$addOnModel = XenForo_Model::create('XenForo_Model_AddOn');
		$addOn = $addOnModel->getAddOnById('AjaxPolling');

		if ($addOn)
		{
			$dw = XenForo_DataWriter::create('XenForo_DataWriter_AddOn');
			$dw->setExistingData('AjaxPolling');

			$dw->preDelete();
			$dw->delete();
		}

		self::_runQuery("
			ALTER TABLE xf_user_option
			ADD COLUMN liveupdate_display_option ENUM('', 'tab_title', 'tab_icon', 'both') NOT NULL DEFAULT 'tab_icon'
		");
	}

	public static function uninstaller()
	{
		self::_runQuery("
			ALTER TABLE xf_user_option DROP COLUMN liveupdate_display_option
		");
	}

	protected static function _runQuery($sql)
	{
		$db = self::_getDb();

		try
		{
			$db->query($sql);
		}
		catch (Zend_Db_Exception $e) {}
	}

	/**
	 * @return Zend_Db_Adapter_Abstract
	 */
	protected static function _getDb()
	{
		if (!self::$_db)
		{
			self::$_db = XenForo_Application::getDb();
		}

		return self::$_db;
	}
}