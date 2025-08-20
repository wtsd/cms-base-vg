<?php
namespace wtsd\common;

class Factory {

	public static function create($class)
	{
		if ($class == 'User') {
			return new \wtsd\user\User();
		}
	}

}