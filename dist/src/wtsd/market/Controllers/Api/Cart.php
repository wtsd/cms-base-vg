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
class Cart extends Api
{
    protected $onlyAuthorized = false;
    protected $onlyAdmin = false;

    public function add()
    {
        $this->code = 200;

        $cart = new cCart();
        $cart->load();

        $result = array();
        $result['status'] = 'error';

        $cart->addToCart(Request::getPost('offer_id'), Request::getPost('quantity'));
        $cart->save();

        $result['status'] = 'ok';
        $result['cartCount'] = $cart->getCartCount();
        $result['offers'] = $cart->getCart();
        $result['deliveryCost'] = $cart->countDelivery($cart->sum());

        $result['sum'] = $cart->sum();
        $result['deliveryCost'] = $cart->countDelivery($cart->sum());

        return $result;        
    }

    public function clear()
    {
        $this->code = 200;

        $cart = new cCart();
        $cart->load();

        $result = array();
        $result['status'] = 'error';

        $cart->clear();
        $cart->save();

        $result['status'] = 'ok';
        $result['cartCount'] = $cart->getCartCount();
        $result['offers'] = $cart->getCart();
        $result['msg'] = 'Корзина успешно очищена!';

        $result['sum'] = $cart->sum();
        $result['deliveryCost'] = $cart->countDelivery($cart->sum());

        return $result;        
    }

    public function remove()
    {
        $this->code = 200;

        $cart = new cCart();
        $cart->load();

        $result = array();
        $result['status'] = 'error';

        $offer_id = Request::getPost('offer_id');
        $cart->removeFromCart($offer_id);
        $cart->save();

        $result['status'] = 'ok';
        $result['msg'] = $offer_id;
        $result['cartCount'] = $cart->getCartCount();

        $result['sum'] = $cart->sum();
        $result['deliveryCost'] = $cart->countDelivery($cart->sum());

        return $result;        
    }

    public function quantity()
    {
        $this->code = 200;

        $cart = new cCart();
        $cart->load();

        $result = array();
        $result['status'] = 'error';

        $offer_id = Request::getPost('offer_id');
        $cart->removeFromCart($offer_id);
        $cart->save();

        $offer_id = Request::getPost('offer_id');
        $quantity = Request::getPost('quantity');
        $offer = new Offer($offer_id);
        $cart->setQuantity($offer_id, $quantity);
        $cart->save();

        $result['status'] = 'ok';
        $result['subtot'] = $quantity * $offer->getPrice();
        $result['msg'] = $offer_id;
        $result['cartCount'] = $cart->getCartCount();
        $result['offers'] = $cart->getCart();

        $result['sum'] = $cart->sum();
        $result['deliveryCost'] = $cart->countDelivery($cart->sum());


        return $result;        
    }
}
