<?php
namespace wtsd\market;

use wtsd\common;
use wtsd\market\Offer;
use wtsd\common\Database;
use wtsd\common\Register;
use wtsd\common\Template;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Cart
{
    // associative array
    private $offers = array();
    private $last_id = 0;
    private static $cookieName = 'offers';

    public static $orderStatuses = [
            'unpaid', 
            'paid', 
            'in_progress',
            'done',
            'deleted',
        ];

    public function __construct()
    {
        $this->load();
    }

    /**
     * Getting offers from the cookies.
     * 
     */
    public function load()
    {
        if (isset($_COOKIE[self::$cookieName])) {
            $info = json_decode($_COOKIE[self::$cookieName], true);
            if ($info) {
                foreach ($info as $id => $offer) {
                    $this->offers[$id] = $offer;
                    $this->last_id = $id;
                }
            }
        }
    }

    /**
     * Saving offers to the cookie.
     * 
     * 
     */
    public function save()
    {
        return setcookie(self::$cookieName, json_encode($this->offers), time() + 3600, '/');
    }

    /**
     * Remove an item from the cart.
     * 
     * 
     */
    public function removeFromCart($id)
    {
        if (isset($this->offers[$id])) {
            unset($this->offers[$id]);
            return true;
        }
        return false;
    }

    /**
     * Remove all items from the cart.
     * 
     * 
     */
    public function clear()
    {
        $this->offers = array();
        $this->last_id = 0;
    }

    /**
     * Get offers assoc array.
     * 
     * 
     */
    public function getCart()
    {
        return $this->offers;
    }

    /**
     * Get items count in the cart.
     * 
     * 
     */
    public function getCartCount()
    {
        return count($this->offers);
    }

    /**
     * Add an item to the cart.
     * 
     * 
     */
    public function addToCart($offer_id, $quantity = 1)
    {
        $obj = new Offer($offer_id);


        foreach ($this->offers as $offer => $values) {
            if ($this->offers[$offer]['offer_id'] == $offer_id) {
                $this->offers[$offer]['quantity'] += $quantity;
                return;
            }
        }
        $this->offers[++$this->last_id] = array('offer_id' => $offer_id, 'price' => $obj->getPrice(), 'quantity' => $quantity);
    }

    public function setQuantity($offer_id, $quantity = 1)
    {
        foreach ($this->offers as $offer => $values) {
            if ($this->offers[$offer]['offer_id'] == $offer_id) {
                $this->offers[$offer]['quantity'] = $quantity;
                return;
            }
        }
        
    }
    ///////////////////

    public function sum()
    {
        $sum = 0;
        if (count($this->offers) > 0) {
            foreach ($this->offers as $id => $offer) {
                $sum += $offer['price'] * $offer['quantity'];
            }
        }
        return $sum;
    }

    public function getOffers()
    {
        $result = array();
        if ($this->getCartCount() > 0) {
            $coffer = new Offer();
            foreach ($this->offers as $id => $offer) {
                $result[$id] = $coffer->loadById($offer['offer_id']);
                $result[$id]['quantity'] = $offer['quantity'];
            }
        }
        return $result;
    }

    public function countDelivery($sum, $city = 'Санкт-Петебрург')
    {
        if (in_array($city, ['Санкт-Петебрург'])) {
            if ($sum >= 4000) {
                return 0;
            }
            if ($sum >= 3000) {
                return 150;
            }
            if ($sum >= 2000) {
                return 250;
            }
            return 350;

            /*
доставка 350р.- внутри КАД курьером, остальное почта.
при заказе от 2000 - доставка 250р.
при заказа от 3000 - доставка 150р.
при заказе от 4000 - доставка бесплатна.
            */
        }
        if ($sum > 2000) {
            return 350;
        }
        return 2000;
    }

    public function getDelivery($orderId)
    {
        $order = $this->getOrderDetails($orderId);
        return $this->countDelivery($order['record']['sum'], $order['record']['city']);
    }

    public function getAdditionalFees($orderId)
    {
        $sum = $this->sum();
        $config = \wtsd\common\Register::get('config');
        $result = [
            'taxes' => $sum * $config['taxes'],
            'delivery' => $this->getDelivery($orderId),
        ];
        return $result;
    }


    public function saveOrder($agree, $name, $lastname, $email, $phone, $city, $address, $comment, $payment_type = 1, $client_type = 1)
    {
        try {
            // @todo: Check $args['i_agree']
            if (isset($agree) && $agree === '1') {
                $this->load();
                $sum = $this->sum();
                if (intval($sum) === 0) {
                    throw new \Exception('Корзина пуста!');
                }
                $config = \wtsd\common\Register::get('config');

                $placeholdersOrder = [
                    ':name' => $name,
                    ':lastname' => $lastname,
                    ':sum' => $sum,
                    ':ip' => $_SERVER['REMOTE_ADDR'],
                    ':email' => $email,
                    ':phone' => $phone,
                    ':city' => $city,
                    ':address' => $address,
                    ':comment' => $comment,

                    ':client_type' => $client_type,
                    ':payment_type' => $payment_type,

                    ':delivery_cost' => (float)$this->countDelivery($sum, $city),
                    ':taxes' => (float)($sum * $config['taxes']),
                ];

                $sqlOrder = "INSERT INTO `tblOrder` SET
                    `name` = :name,
                    `lastname` = :lastname,
                    `email` = :email,
                    `phone` = :phone,
                    `city` = :city,
                    `sum` = :sum,
                    `address` = :address,
                    `comment` = :comment,
                    `ip` = :ip,
                    `client_type` = :client_type,
                    `payment_type` = :payment_type,
                    `status` = 'unpaid',
                    `delivery_cost` = :delivery_cost,
                    `taxes` = :taxes";

                // @todo: Validate email, phone, city
/*                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Неправильный формат email');
                } 
*/
                $orderId = Database::insertQuery($sqlOrder, $placeholdersOrder);
                $offers = $this->getCart();

                $sqlBase = "INSERT INTO `tblOrderOffers` (`order_id`, `offer_id`, `quantity`) VALUES ";
                $sqlValues = array();
                foreach ($offers as $offer) {
                    $sqlValues[] = sprintf("(%d, %d, %d)", $orderId, $offer['offer_id'], $offer['quantity']);
                }
                $sqlOffers = $sqlBase . implode(', ', $sqlValues);
                Database::insertQuery($sqlOffers);

                return $orderId;

            } else {
                // @todo: Send message about required i_agree field
                throw new \Exception('Нужно согласиться с условиями!');
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
            return 0;
            //return array('result' => 0, 'message' => $e->getMessage(), 'page_title' => 'Ошибка!');
        }

    }

    public function changeOrderStatus($orderId, $status = 'paid')
    {
        if (in_array($status, self::$orderStatuses)) {
            $sql = "UPDATE `tblOrder` SET `status` = :status WHERE `id` = :id";
            $placeholders = ['status' => $status, ':id' => $orderId];
            Database::updateQuery($sql, $placeholders);
        }
    }

    public function getOrderDetails($orderId)
    {
        $order = new \wtsd\market\Order();
        return $order->getOrderDetails($orderId);
    }

    public function notifyAdmin($orderId)
    {

        $config = Register::get('config');
        $labels = Register::get('lang');
        
        
        $view = new Template('default');
        $view->assignAll($this->getOrderDetails($orderId));
        $body = $view->render('email/order-mail.tpl');


        try {

            $mail = new \PHPMailer();
            $mail->SetFrom($config['noreplymail'], 'Магазин Хороший Чай');
            $mail->addAddress($config['adminmail']);
            $mail->addAddress('warlockfx@gmail.com');
            $mail->isHTML(true);

            $mail->CharSet = 'UTF-8';
            $mail->Subject = '[#' . $orderId . '] ' . $labels['subject'];
            $mail->Body    = $body;

            $errTitle = 'Заказ оформлен, но письмо не было отправлено!';

            $mail->send();

            \wtsd\common\Log::write('email', array('sent', $config['noreplymail'], $config['adminmail']));
        } catch (phpmailerException $e) { 
            \wtsd\common\Log::write('email', array('error', $e->errorMessage()));
        }

    }

    public function notifyClient($orderId)
    {

        $config = Register::get('config');
        $labels = Register::get('lang');
        
        $order = $this->getOrderDetails($orderId);
        
        $view = new Template('default');
        $view->assign('order', $order);
        $body = $view->render('email/order-mail-client.tpl');


        try {

            $mail = new \PHPMailer();
            $mail->SetFrom($config['noreplymail'], 'Магазин Хороший Чай');
            $mail->addAddress($order['record']['email']);
            $mail->addAddress('warlockfx@gmail.com');
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->CharSet = 'UTF-8';
            $mail->Subject = '[#'.$orderId.'] Вы сделали заказ на сайте Хорошего чая';
            $mail->Body    = $body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $errTitle = 'Заказ оформлен, но письмо не было отправлено!';

            $mail->send();

            \wtsd\common\Log::write('email', array('sent', $config['noreplymail'], $config['adminmail']));
        } catch (phpmailerException $e) { 
            \wtsd\common\Log::write('email', array('error', $e->errorMessage()));
        }
    }

    public function getList($page = 1)
    {
        $order = new \wtsd\market\Order();
        return $order->getList($page);
    }


    public function getOffersByOrder($order_id)
    {
        $order = new \wtsd\market\Order();
        return $order->getOffersByOrder($order_id);
    }

    public function saveCookies($orderId)
    {
        $expires = time() + 86400 * 365 * 2;
        $order = $this->getOrderDetails($orderId);
        setcookie('order-data', json_encode($order['record']), $expires, '/');
    }
}