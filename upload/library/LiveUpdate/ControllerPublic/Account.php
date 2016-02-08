<?php

class LiveUpdate_ControllerPublic_Account extends XFCP_LiveUpdate_ControllerPublic_Account
{
	public function actionPreferencesSave()
	{
		$parent = parent::actionPreferencesSave();

		if ($this->_request->isPost())
		{
			if (!empty(XenForo_Visitor::getInstance()->permissions['general']['liveUpdateChangeOptions']))
			{
				$liveUpdateOption = $this->_input->filterSingle('liveupdate_display_option', XenForo_Input::JSON_ARRAY);

				$preparedOptions = array();
				foreach ($liveUpdateOption AS $key => $option)
				{
					if (in_array($option, array('tab_title', 'tab_icon', 'notifications_api')))
					{
						$preparedOptions[] = $option;
					}
				}
			}
			else
			{
				$liveUpdateOption = XenForo_Application::getOptions()->liveUpdateDefaultOptions;
				$preparedOptions = array_keys($liveUpdateOption);
			}

			$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
			$writer->setExistingData(XenForo_Visitor::getUserId());
			$writer->set('liveupdate_display_option', $preparedOptions, 'xf_user_option');
			$writer->save();
		}

		return $parent;
	}
}