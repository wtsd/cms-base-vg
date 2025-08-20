<?php
namespace wtsd\event;

use wtsd\common;
use wtsd\common\ProtoClass;
use wtsd\common\Database;
use wtsd\common\Register;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Slot extends ProtoClass
{
    
    protected $c_type = 'slot';

    public $_table = 'tblTimeslot';

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('wday');
        $this->addField('time');
        $this->addField('price');
        $this->addField('status', 'cmb', true);


        if (is_numeric($id)) {
            parent::__construct($id);
        } elseif ($id !== '') {
            $rewrite = $id;
            $arr = self::loadFromRewrite($rewrite);
            if (isset($arr['id'])) {
                parent::__construct($arr['id']);
            }
        }

    }
}