<?php
namespace wtsd\market\Controllers\Api;

use wtsd\common\Controllers\Api;
use wtsd\common\Request;
use wtsd\market\Cart as cCart;
use wtsd\market\Offer;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Orders extends Api
{
	public function intcomment()
	{
		$order = new \wtsd\market\Order();
		$id = Request::getPost('id');
		$int_comment = Request::getPost('int_comment');
		$order->updateIntComment($id, $int_comment);

		$this->code = 200;
		return ['status' => 'ok'];
	}
}