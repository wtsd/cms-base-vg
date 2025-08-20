<?php
namespace wtsd\user\Controllers\Api;

use wtsd\common\Controllers\Api;

class Authorize extends Api
{
    protected $onlyAuthorized = false;
    protected $onlyAdmin = false;

    public function login($args)
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
