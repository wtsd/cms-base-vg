<?php
namespace wtsd\user\Controllers\Api;

use wtsd\common\Controllers\Api;

class Users extends Api
{
	protected $onlyAdmin = true;
    
    protected function getObject()
    {
        return \wtsd\common\Factory::create('User');
    }

    protected function doValidate($placeholders)
    {
        return \wtsd\common\Factory::create('User')->validate($placeholders);
    }

    public function columns()
    {

		    return [  
              ["data" => "id"],
              ["data" => "email"],
              ["data" => "name"],
              ["data" => "descr"],
              ["data" => "group_name"],
              ["data" => "tel"],
              ["data" => "actions"],
          ];
    }

    public function authorize($args)
    {
      $email = $args['email'];
      $passwd = $args['passwd'];

      $user = \wtsd\common\Factory::create('User');
      $this->code = 200;
      if ($user->authenticate($email, $passwd)) {
        return [
          'status' => 'ok',
          'name' => $user->getName(),
          'uri' => '/adm/dashboard/',
        ];
      } else {
        return [
          'status' => 'error',
          'msg' => 'В доступе отказано!',
        ];
      }
    }
}
