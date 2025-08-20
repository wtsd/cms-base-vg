<?php
namespace wtsd\user\Controllers\Api;

use wtsd\common\Controllers\Api;

class Groups extends Api
{
	protected $onlyAdmin = true;
    
    protected function getObject()
    {
        return new \wtsd\user\Group();
    }

    protected function doValidate($placeholders)
    {
        return \wtsd\user\Group::validate($placeholders);
    }

    public function columns()
    {

    	return [
       	  ["data" => "id"],
          ["data" => "name"],
          ["data" => "comment"],
          ["data" => "status"],
          ["data" => "actions"],
      ];
    }
}
