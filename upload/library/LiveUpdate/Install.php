<?php

class LiveUpdate_Install
{
	protected static $_db = null;

	public static function installer($previous)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			throw new XenForo_Exception('This add-on requires XenForo 1.2.0 or higher.', true);
		}

		$version = is_array($previous) ? $previous['version_id'] : 0;

		if (!$version)
		{
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
		else
		{
			if ($version < 4010070)
			{
				self::_runQuery("
					ALTER TABLE xf_user_option
					CHANGE COLUMN liveupdate_display_option liveupdate_display_option MEDIUMBLOB NULL DEFAULT NULL
				");


				/**
				 * Enable the new notifications API by default, and reformat the field contents accordingly
				 * Keep the add-on disabled entirely for any one who has already explicitly disabled it.
				 */

				self::_runQuery("
					UPDATE xf_user_option
					SET liveupdate_display_option = ?
					WHERE liveupdate_display_option = 'tab_title'
				", json_encode(array('tab_title', 'notifications_api')));

				self::_runQuery("
					UPDATE xf_user_option
					SET liveupdate_display_option = ?
					WHERE liveupdate_display_option = 'tab_icon'
				", json_encode(array('tab_icon', 'notifications_api')));

				self::_runQuery("
					UPDATE xf_user_option
					SET liveupdate_display_option = ?
					WHERE liveupdate_display_option = 'both'
				", json_encode(array('tab_title', 'tab_icon', 'notifications_api')));

				self::_runQuery("
					UPDATE xf_user_option
					SET liveupdate_display_option = ?
					WHERE liveupdate_display_option = ''
				", json_encode(array()));
			}
		}
	}

	public static function uninstaller()
	{
		self::_runQuery("
			ALTER TABLE xf_user_option DROP COLUMN liveupdate_display_option
		");
	}

	protected static function _runQuery($sql, $bind = array())
	{
		$db = self::_getDb();

		try
		{
			$db->query($sql, $bind);
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