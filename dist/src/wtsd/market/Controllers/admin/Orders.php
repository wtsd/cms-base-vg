<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\market\Cart;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Orders extends AdminController
{
    protected $template = 'market/orders.tpl';
    
    public function run()
    {
        $page = intval(Request::parseUrl(3));

        $page = ($page > 0) ? $page : 1;

        $batch = 20;

        $order = new \wtsd\market\Order();
        $this->contents['records'] = $order->getList($page, $batch);
        $this->contents['cnt'] = $order->getListCount();

        $this->contents['pages'] = ceil($this->contents['cnt'] / $batch);
        $this->contents['preUrl'] = '/adm/orders/browse/%d/';
        $this->contents['curPage'] = $page;
        $this->contents['preUrlSprint'] = true;


        return $this->contents;
    }

    public function changeStatus()
    {
    	$status = Request::parseUrl(4);
    	$id = Request::parseUrl(3);

    	if (!in_array($status, Cart::$orderStatuses)) {
    		$this->code = 400;
    		return;
    	}

    	$cart = new Cart();
    	$cart->changeOrderStatus($id, $status);

    	$contentsArray = [];
    	$contentsArray['records'] = $cart->getList();
    	return $contentsArray;
    }
}