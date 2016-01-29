<?php

class LiveUpdate_ControllerPublic_Update extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
		return $this->responseMessage(new XenForo_Phrase('liveupdate_request_completed_successfully'));
	}
}