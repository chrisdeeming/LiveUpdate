<?php

class LiveUpdate_ControllerPublic_Update extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex()
	{
		if (!XenForo_Visitor::getUserId())
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('index')
			);
		}
		return $this->responseMessage(new XenForo_Phrase('liveupdate_request_completed_successfully'));
	}
}