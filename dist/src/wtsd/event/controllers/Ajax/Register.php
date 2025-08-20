<?php
namespace wtsd\event\controllers\Ajax;

use wtsd\common\Controllers\General\Ajax;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Register extends Ajax
{

    public function run()
    {
        
        $schedule = new \wtsd\event\Schedule();
        // @todo: Saving routine
        $date = Request::getPost('date');
        $slot_id = Request::getPost('slot_id');
        $name = Request::getPost('name');
        $tel = Request::getPost('tel');
        $participants = intval(Request::getPost('participants'));
        $comment = Request::getPost('comment');
        $email = Request::getPost('email');
        $event_id = Request::getPost('event-id');
        $rewrite = Request::getPost('rewrite');
        $ip = $_SERVER['REMOTE_ADDR'];

        if ($schedule->isAvailable($slot_id, $date, $event_id)) {
            $id = $schedule->reserve($date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip);
            
            $schedule->notify($id, $date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip);

            $event = new \wtsd\event\Event();
            $arr = array(
                'date' => $date,
                'time' => $schedule->toTime($slot_id),
                'event' => $event->getById($event_id)
                );

            $view = new \wtsd\common\Template('default');
            $view->assignAll($arr);
            $html = $view->render('events/event-success-ajax.tpl');

            return array('status' => 'ok', 'msg' => 'Игра успешно забронирована', 'html' => $html, 'date' => $date, 'slot_id' => $slot_id, 'rewrite' => $rewrite);
        } else {
            return array('status' => 'occupied', 'msg' => 'К сожалению, на это время игра уже была забронирована.');
        }
    }
    
}
