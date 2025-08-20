<?php
namespace wtsd\market\Controllers\Admin;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Dashboard extends AdminController
{
    protected $onlyAuthorized = true;
    protected $defaultUrl = '/dashboard/';

    public function run()
    {
        $this->template = 'market/dashboard.tpl';

        $sql = "SELECT
                    sum(`sum`) AS `sum`,
                    DATE_FORMAT(`cdate`,'%Y-%m') AS `date`,
                    -- concat(year(`cdate`), '-', month(`cdate`)) AS `date`,
                    count(*) AS `cnt`
                FROM `tblOrder` 
                WHERE `status` != 'deleted'
                GROUP BY 
                    `date`
                ORDER BY
                    `date` DESC";
        $monthlyOrders = \wtsd\common\Database::selectQuery($sql);

        $this->contents['stat']['orders'] = $monthlyOrders;

        $sqlTotal = "SELECT
                sum(`sum`) AS `sum`,
                count(*) AS `cnt`
            FROM `tblOrder` 
            WHERE `status` != 'deleted'
            ";
        $total = \wtsd\common\Database::selectQuery($sqlTotal, null, true);
        $this->contents['stat']['total'] = $total;

        $sqlOffers = "SELECT count(*) AS `cnt` FROM `tblOffer`";
        $offers = \wtsd\common\Database::selectQuery($sqlOffers, null, true);
        $this->contents['stat']['offers'] = $offers;

        $cart = new \wtsd\market\Cart();
        $sqlLastOrder = "SELECT * FROM `tblOrder` WHERE `status` != 'deleted' ORDER BY `cdate` DESC LIMIT 1";
        $lastOrder = \wtsd\common\Database::selectQuery($sqlLastOrder, null, true);
        $lastOrder['offers'] = $cart->getOffersByOrder($lastOrder['id']);

        $this->contents['lastOrder'] = $lastOrder;

        return $this->contents;
    }

}
