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
class Order
{

    public static $orderStatuses = [
            'unpaid', 
            'paid', 
            'in_progress',
            'done',
            'deleted',
        ];

    public function updateIntComment($id, $int_comment)
    {
    	$sql = "UPDATE `tblOrder` SET `int_comment` = :int_comment WHERE `id` = :id";
    	$placeholders = [':id' => $id, ':int_comment' => $int_comment];

    	Database::updateQuery($sql, $placeholders);
    }



    public function getList($page = 1, $batch = 20)
    {
        $offset = 0;
        if (intval($page) > 0) {
            $offset = (intval($page) - 1) * $batch;
        }

        $placeholders = array(
            ':batch' => array('value' => $batch, 'type' => 'int'),
            ':offset' => array('value' => $offset, 'type' => 'int'),
            );

        $sql = "SELECT * FROM `tblOrder` WHERE `status` != 'deleted' ORDER BY `id` DESC LIMIT :offset, :batch";
        $records = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($records); $i++) {
            $records[$i]['offers'] = $this->getOffersByOrder($records[$i]['id']);
        }

        return $records;
    }


    public function getOffersByOrder($order_id)
    {
        $sql = "SELECT `o`.*, `oo`.`quantity`, `oi`.`fname` FROM `tblOrderOffers` `oo` INNER JOIN `tblOffer` `o` ON `oo`.`offer_id` = `o`.`id` INNER JOIN `tblOfferImages` `oi` ON `oi`.`offer_id` = `o`.`id` WHERE  `oi`.`is_main` = 1 AND `oo`.`order_id` = :order_id";
        $placeholders = array(':order_id' => $order_id);
        return Database::selectQuery($sql, $placeholders);
    }

    public function getListCount()
    {

        $sql = "SELECT count(*) AS `cnt` FROM `tblOrder` WHERE `status` != 'deleted'";
        $record = Database::selectQueryBind($sql, null, true);

        return $record['cnt'];
    }


    public function getOrderDetails($orderId)
    {
        $sql_select = "SELECT * FROM `tblOrder` WHERE `id` = :id";
        $placeholders_sel = array(':id' => $orderId);
        return [
            'record' => Database::selectQuery($sql_select, $placeholders_sel, true),
            'offers' => $this->getOffersByOrder($orderId),
            ];
    }

}