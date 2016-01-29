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
}