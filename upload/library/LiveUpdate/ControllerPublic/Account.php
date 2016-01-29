<?php

class LiveUpdate_ControllerPublic_Account extends XFCP_LiveUpdate_ControllerPublic_Account
{
	public function actionPreferencesSave()
	{
		$parent = parent::actionPreferencesSave();

		if ($this->_request->isPost())
		{
			$liveUpdateOption = $this->_input->filterSingle('liveupdate_display_option', XenForo_Input::STRING);

			$writer = XenForo_DataWriter::create('XenForo_DataWriter_User');
			$writer->setExistingData(XenForo_Visitor::getUserId());
			$writer->set('liveupdate_display_option', $liveUpdateOption, 'xf_user_option');
			$writer->save();
		}

		return $parent;
	}
}