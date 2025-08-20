<?php
namespace wtsd\market\Controllers\Api;

use wtsd\common\Controllers\Api;

class Branches extends Api
{
	protected $onlyAdmin = true;
    
    protected function getObject()
    {
        return new \wtsd\user\Branch();
    }

    protected function doValidate($placeholders)
    {
        return \wtsd\user\Branch::validate($placeholders);
    }

    public function columns()
    {

		return [  
              ["data" => "id"],
              ["data" => "name"],
              ["data" => "address"],
              ["data" => "is_public"],
              ["data" => "is_active"],
              ["data" => "tel"],
              ["data" => "actions"],
          ];
    }

}
