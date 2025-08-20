<?php
namespace wtsd\event\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Request;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Event extends Controller
{
    protected $template = 'events/event.tpl';

    public function run()
    {
        $rewrite = Request::parseUrl(1);
        $action = Request::parseUrl(2);

        $contentsArray = [];

        // Get the requested event
        $event = new \wtsd\event\Event();
        $event->getByRewrite($rewrite);
        if ($action == '') {

        }

        // Load schedule and pass to the template
        $schedule = new \wtsd\event\Schedule();
        $contentsArray['date_start'] = strtotime('now');
        $contentsArray['date_end'] = strtotime('+2 weeks');
        $contentsArray['schedule'] = $schedule->get($event->getId());

        if ($action == 'success') {
            $contentsArray['date'] = Request::parseUrl(3);
            $slot = new \wtsd\event\Schedule();

            $contentsArray['time'] = $slot->toTime(Request::parseUrl(4));
            $this->template = 'events/event-success.tpl';
        }
        if ($action == 'occupied') {
            $contentsArray['event'] = $event->getByRewrite($rewrite);
            $this->template = 'events/event-occupied.tpl';
        }
        if ($action == 'submit') {

            $schedule = new \wtsd\event\Schedule();
            if (Request::getPost('doSave') !== null) {
                // @todo: Saving routine
                $date = Request::getPost('date');
                $slot_id = Request::getPost('slot_id');
                $name = Request::getPost('name');
                $tel = Request::getPost('tel');
                $participants = intval(Request::getPost('participants'));
                $comment = Request::getPost('comment');
                $email = Request::getPost('email');
                $event_id = Request::getPost('event-id');
                $ip = $_SERVER['REMOTE_ADDR'];

                if ($schedule->isAvailable($slot_id, $date, $event_id)) {
                    //if (filter_var($email, FILTER_VALIDATE_EMAIL)) { 
                        $id = $schedule->reserve($date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip);
                        
                        $schedule->notify($id, $date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip);
                        $this->code = 302;
                        $this->redirectUrl = '/event/' . $rewrite . '/success/' . $date . '/' . $slot_id;
                        return [];



                    /*} else {
                        $this->code = 302;
                        $this->redirectUrl = '/event/' . $rewrite . '/submit/' . $slot_id . '/' . $date;
                        return [];
                    }*/

                } else {
                    $this->code = 302;
                    $this->redirectUrl = '/event/' . $rewrite . '/occupied/';
                    return [];
                }
                
            } else {
                $this->template = 'events/event-registration-form.tpl';
                
                $date = Request::parseUrl(4);
                $slot_id = Request::parseUrl(3);

                $contentsArray['slot_id'] = $slot_id;
                $contentsArray['time'] = $schedule->toTime($slot_id);
                $contentsArray['date'] = $date;
                $contentsArray['price'] = $schedule->getPriceBySlotId($slot_id);
                // Load schedule and pass to the template

                $event_id = $event->getId();
                $schedule = new \wtsd\event\Schedule();
                if (!$schedule->isAvailable($slot_id, $date, $event_id)) {
                    $this->code = 302;
                    $this->redirectUrl = '/event/' . $rewrite . '/occupied/';
                    return [];
                }
                $contentsArray['schedule'] = $schedule->get($event->getId());
            }
        }
        if ($action == 'comments') {
            $this->template = 'events/event-comments.tpl';
        }

        if ($action == 'records') {
            $this->template = 'events/event-records.tpl';
            $schedule = new \wtsd\event\Schedule();
            $contentsArray['scores'] = $schedule->getWinners($event->getId());
        }

        $contentsArray['event'] = $event->getByRewrite($rewrite);
        if (!$contentsArray['event']) {
            $this->code = 302;
            $this->redirectUrl = '/';
            return [];
        }
        
        return $contentsArray;
    }
    
}
