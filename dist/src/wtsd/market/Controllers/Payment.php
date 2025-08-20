<?php
namespace wtsd\market\Controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Payment extends Controller
{

    public function run()
    {
        $this->template = 'market-payment.tpl';
        return array();
    }

    public function success()
    {

    	if (!isset($_REQUEST['OutSum'])
    		|| !isset($_REQUEST['Shp_item'])
    		|| !isset($_REQUEST['InvId'])) {
    		die('error!');
    	}
    	$out_summ = $_REQUEST["OutSum"];
		$shp_item = $_REQUEST["Shp_item"];
		$orderId = $_REQUEST["InvId"];

		$crc = $_REQUEST["SignatureValue"];

		$payment = new \wtsd\common\Payment($out_summ, $shp_item, $orderId);

		if ($payment->checkResult($crc)) {
			$this->template = 'market-success.tpl';
			$cart = new \wtsd\market\Cart();
			
			// Change order's status
			$cart->changeOrderStatus($orderId, 'paid');
			//$cart->notify();
			$cart->clear();
			$cart->save();

			$order = $cart->getOrderDetails($orderId);
			return [
				'orderId' => $orderId,
				'order' => $order,
			];
		} else {
			$this->template = 'market-fail.tpl';
			return [
				'orderId' => $orderId,
			];
		}
    }


    public function fail()
    {
		$this->template = 'market-fail.tpl';
		$orderId = $_REQUEST["InvId"];
		return [
			'orderId' => $orderId,
		];
    }
}
