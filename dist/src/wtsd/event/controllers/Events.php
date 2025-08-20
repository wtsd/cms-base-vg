<?php
namespace wtsd\event\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Events extends Controller
{
    protected $template = 'events/events.tpl';

    public function run()
    {
        // @todo: Get all events and pass them to the template
        $event = new \wtsd\event\Event();
        $cat = new \wtsd\content\Category();

        $contentsArray = array(
            'events' => $event->getAll(),
            'obj' => $cat->buildContents('events')['row'],
            );

        return $contentsArray;
    }
    
}
