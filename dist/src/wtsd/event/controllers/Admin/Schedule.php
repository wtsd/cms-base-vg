<?php
namespace wtsd\event\controllers\Admin;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\AdminController;
use wtsd\content\Schedule as cSchedule;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Schedule extends AdminController
{

    public function run()
    {
        $action = Request::parseUrl(2);
        if (in_array($action, array('browse', 'lst', 'listing', ''))) {
            return $this->listing();
        } elseif (in_array($action, array('frm', 'edit', 'form', 'add'))) {
            return $this->form();
        }
    }

    public function listing()
    {
        $obj = new \wtsd\event\Schedule();

        $page = Request::parseUrl(3);
        $date = Request::getGet('date');
        $q = trim(Request::getGet('q'));

        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page, $q);
        $contentsArray['date'] = Request::getGet('date');
        $contentsArray['q'] = trim(Request::getGet('q'));

        $contentsArray['stat']['all'] = $obj->getStatisticsAll();
        $contentsArray['stat']['month'] = $obj->getStatisticsMonth();
        $contentsArray['stat']['prevmonth'] = $obj->getStatisticsMonthPrev();

        $contentsArray['stat']['moneyall'] = $obj->getStatisticsAllMoney();
        $contentsArray['stat']['moneymonth'] = $obj->getStatisticsMonthMoney();
        $contentsArray['stat']['moneyprevmonth'] = $obj->getStatisticsMonthPrevMoney();
        
        $this->template = 'lst-schedule.tpl';
        return $contentsArray;
    }    

    public function form($id = 0)
    {
        $obj = new \wtsd\event\Schedule();
        $event = new \wtsd\event\Event();

        $id = Request::parseUrl(3);

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'schedule';
        $contentsArray['id'] = $id;
        $contentsArray['slots'] = $obj->getSlots();
        $contentsArray['events'] = $event->getAll();

        $this->template = 'frm-schedule.tpl';
        return $contentsArray;
    }
    
    public function save()
    {
        $obj = new \wtsd\event\Schedule();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new \wtsd\event\Schedule();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
    
}