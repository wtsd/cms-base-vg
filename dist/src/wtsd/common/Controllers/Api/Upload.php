<?php
namespace wtsd\common\Controllers\Api;

use wtsd\common\Controllers\Api;

class Upload extends Api
{
	protected $onlyAdmin = true;
    /*
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
    }*/
    public function screenshot($args)
    {
        $user = \wtsd\common\Factory::create('User');
        if ($user->isAdmin()) {
            $result = array('status' => 'error');
            $image = $args['data']['image'];

            if ($image) {
                $data = base64_decode($image);
                $fname = uniqid() . '.png';
                $file = ROOT . '/temp/' . $fname;
                $success = file_put_contents($file, $data);
                $result['fname'] = $fname;
                $result['status'] = 'ok';
            }

            return $result;
        }

    }
}
