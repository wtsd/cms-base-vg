<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Controller;
use wtsd\market\Cart as cCart;
use wtsd\common\Register;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Cart extends Controller
{
    protected $cart = null;

    public function run()
    {

        return $this->show();

    }

    public function show()
    {

        $this->cart = new cCart();
        $this->cart->load();

        $this->template = 'cart.tpl';
        $labels = Register::get('lang');

        
        $this->contents['sum'] = $this->cart->sum();
        $this->contents['offers'] = $this->cart->getOffers();

        $this->contents['deliveryCost'] = $this->cart->countDelivery($this->cart->sum());
        $this->contents['payment_types'] = $labels['payment_types'];
        $this->contents['cities'] = $labels['cities'];

        if (count($this->contents['offers']) == 0) {
            $offer = new \wtsd\market\Offer();
            $this->contents['recommended'] = $offer->getSpecial(9);
        }

        if (isset($_COOKIE['order-data'])) {
            $this->contents['cookie_values'] = (array) json_decode($_COOKIE['order-data']);
        } else {
            $this->contents['cookie_values'] = [];
        }

        $this->contents['page_title'] = $labels['cart']['viewcart'];
        return $this->contents;
    }

    public function doprint()
    {

        $this->cart = new cCart();
        $this->cart->load();

        $this->template = 'cart-print.tpl';
        $labels = Register::get('lang');

        
        $this->contents['sum'] = $this->cart->sum();
        $this->contents['offers'] = $this->cart->getOffers();

        $this->contents['page_title'] = $labels['cart']['printcart'];
        return $this->contents;
    }

    public function placeorder()
    {

        $this->cart = new cCart();
        $this->cart->load();

        $this->template = 'cart-checkout.tpl';
        $this->contents = array();

        $agree = Request::getPost('i_agree');
        $name = Request::getPost('name');
        $lastname = Request::getPost('lastname');
        $client_type = Request::getPost('client_type');
        $email = Request::getPost('email');
        $phone = Request::getPost('phone');
        $city = Request::getPost('city');
        $address = Request::getPost('address');
        $payment_type = Request::getPost('payment_type');
        $comment = Request::getPost('comment');

        $this->contents = $this->cart->saveOrder($agree, $name, $lastname, $email, $phone, $city, $address, $comment, $payment_type, $client_type);
            
        return $this->contents;
    }

    public function finish()
    {
        

        $this->cart = new cCart();
        $this->cart->load();

        $this->contents = array();

        $agree = Request::getPost('i_agree');
        $name = Request::getPost('name');
        $lastname = Request::getPost('lastname');
        $email = Request::getPost('email');
        $phone = Request::getPost('phone');
        $city = Request::getPost('city');
        $address = Request::getPost('address');
        $comment = Request::getPost('comment');
        $payment_type = Request::getPost('payment_type');


        $orderId = $this->cart->saveOrder($agree, $name, $lastname, $email, $phone, $city, $address, $comment, $payment_type);
        
        $this->cart->saveCookies($orderId);

        $this->contents['result'] = 0;
        $this->contents['message'] = 'Ошибка сохранения заказа!';
        $this->contents['page_title'] = 'ОШИБКА';
        $this->contents['orderId'] = $orderId;
        $this->contents['payment_type'] = $payment_type;

        if ($orderId > 0) {

            $order = $this->cart->getOrderDetails($orderId);
            $payment = new \wtsd\common\Payment($order['record']['sum'], count($order['offers']), $orderId);


            if ($this->environment == 'PRODUCTION') {
                $this->cart->notifyAdmin($orderId);
                $this->cart->notifyClient($orderId);
            }

            $this->contents = [
                'result' => 1, 'message' => 'Заказ успешно оформлен!', 'page_title' => 'Заказ оформлен!', 'orderId' => $orderId,
                    'order' => $order,
                    'payment_type' => $payment_type,
                    'deliveryCost' => $this->cart->getDelivery($orderId),
                    'sum' => $this->cart->sum(),
                ];

            if ($payment_type == 'online') {
                $this->contents['details'] = $payment->getVariables();
            }

            $this->cart->clear();
            $this->cart->save();
        }
        // Create order and get its ID
        // Redirect to Robokassa
        $this->template = 'cart-checkout.tpl';
            
        return $this->contents;
    }
}