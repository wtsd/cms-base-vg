<?php
namespace wtsd\event;

use wtsd\common;
use wtsd\common\Database;
use wtsd\common\Register;
use wtsd\common\ProtoClass;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Schedule extends ProtoClass 
{
    const SQL_GET_IMAGES = 'SELECT * FROM `%s` WHERE `%s` = :%s';

    protected $c_type = 'schedule';

    public $_table = 'tblSchedule';

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('date', 'text');
        $this->addField('slot_id', 'cmb');
        $this->addField('email', 'text', false);
        $this->addField('name', 'text');
        $this->addField('tel', 'text');
        $this->addField('comment', 'textarea', false);
        $this->addField('price', 'text');
        $this->addField('participants', 'text');
        $this->addField('winner', 'text');
        $this->addField('score', 'text');
        $this->addField('event_id', 'cmb');
        $this->addField('cdate', 'time');
        $this->addField('status', 'cmb', true);
        
        parent::__construct($id);
        
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'date' => '',
            'slot_id' => '',
            'email' => '',
            'name' => '',
            'tel' => '',
            'comment' => '',
            'price' => '',
            'participants' => '2',
            'winner' => '',
            'score' => '60.0',
            'event_id' => '',
            'cdate' => date('Y-m-d H:i:s'),
            'status' => '1',
            );
    }

    protected function _getCount()
    {
        $filter = trim(\wtsd\common\Request::getGet('q'));
        $date = \wtsd\common\Request::getGet('date');
        if ($filter) {
            return $this->getCountByFilter($filter);
        } elseif ($date) {
            if ($date == 'today') {
                $fromDate = date('Y-m-d', strtotime('today'));
                $toDate = date('Y-m-d', strtotime('today'));
            }
            if ($date == 'tomorrow') {
                $fromDate = date('Y-m-d', strtotime('tomorrow'));
                $toDate = date('Y-m-d', strtotime('tomorrow'));
            }
            if ($date == 'week') {
                $fromDate = date('Y-m-d', strtotime('today'));
                $toDate = date('Y-m-d', strtotime('+7 days'));
            }

            return $this->getCountByDate($fromDate, $toDate);
        } else {
            return $this->getCountAll();
        }
    }

    protected function getCountAll()
    {
        $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s`", $this->_table);
        $rows = Database::selectQuery($sql_all);

        return $rows[0]['cnt'];
    }

    protected function getCountByDate($fromDate, $toDate)
    {
        $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s` WHERE `date` BETWEEN :from AND :to", $this->_table);
        $placeholders = array(':from' => $fromDate, ':to' => $toDate);
        $rows = Database::selectQuery($sql_all, $placeholders);

        return $rows[0]['cnt'];
    }

    protected function getCountByFilter($filter)
    {
        $sql = "SELECT count(`s`.`id`) AS `cnt` FROM `tblSchedule` `s` LEFT JOIN `tblEvent` `e` ON `e`.`id` = `s`.`event_id` INNER JOIN `tblTimeslot` `ts` ON `s`.`slot_id` = `ts`.`id` WHERE  `s`.`name` LIKE :name OR `s`.`tel` LIKE :tel OR `s`.`email` LIKE :email OR `s`.`comment` LIKE :comment";
        $placeholders = [];
        $placeholders[':name'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        $placeholders[':tel'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        $placeholders[':email'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        $placeholders[':comment'] = array('type' => 'string', 'value' => '%'.$filter.'%');

        /*$sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s` WHERE `rdate` BETWEEN :from AND :to", $this->_table);
        $placeholders = array(':from' => $fromDate, ':to' => $toDate);*/
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows[0]['cnt'];
    }
    protected function _getRecords($off, $perp)
    {
        $date = \wtsd\common\Request::getGet('date');
        $filter = trim(\wtsd\common\Request::getGet('q'));

        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );

        $where = '1';
        if ($date == 'today') {
            $where = ' `s`.`date` = :date';
            $placeholders[':date'] = array('type' => 'string', 'value' => date('Y-m-d', strtotime('today')));
        }
        if ($date == 'tomorrow') {
            $where = ' `s`.`date` = :date ';
            $placeholders[':date'] = array('type' => 'string', 'value' => date('Y-m-d', strtotime('tomorrow')));
        }
        if ($date == 'week') {
            $where = ' `s`.`date` BETWEEN :from AND :to';
            $placeholders[':from'] = array('type' => 'string', 'value' => date('Y-m-d', strtotime('today')));
            $placeholders[':to'] = array('type' => 'string', 'value' => date('Y-m-d', strtotime('+7 days')));
        }

        if ($filter != null) {
            $where = ' `s`.`name` LIKE :name OR `s`.`tel` LIKE :tel OR `s`.`email` LIKE :email OR `s`.`comment` LIKE :comment ';
        }

        $sql = "SELECT `s`.*, `e`.`name` AS `event_name`, DATE_FORMAT(`ts`.`time`, '%k:%i') AS `time`, CONCAT(`s`.`date`, ' ', `ts`.`time`) AS `timestamp` FROM `tblSchedule` `s` LEFT JOIN `tblEvent` `e` ON `e`.`id` = `s`.`event_id` INNER JOIN `tblTimeslot` `ts` ON `s`.`slot_id` = `ts`.`id` WHERE {$where} ORDER BY `timestamp` DESC, `s`.`id` DESC LIMIT :off, :perpage";
        if ($filter) {
            $placeholders[':name'] = array('type' => 'string', 'value' => '%'.$filter.'%');
            $placeholders[':tel'] = array('type' => 'string', 'value' => '%'.$filter.'%');
            $placeholders[':email'] = array('type' => 'string', 'value' => '%'.$filter.'%');
            $placeholders[':comment'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        }
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }

    public function getAll()
    {
        $sql = "SELECT `s`.*, `e`.`name` AS `event_name`, DATE_FORMAT(`ts`.`time`, '%k:%i') AS `time`, CONCAT(`s`.`date`, ' ', `ts`.`time`) AS `timestamp` FROM `tblSchedule` `s` LEFT JOIN `tblEvent` `e` ON `e`.`id` = `s`.`event_id` INNER JOIN `tblTimeslot` `ts` ON `s`.`slot_id` = `ts`.`id` GROUP BY `s`.`id` ORDER BY `timestamp` DESC, `s`.`id` DESC ";

        $result = Database::selectQuery($sql);

        return $result;
    }

    public function getByEvent($event_id)
    {
        $date_f = new \DateTime();
        $date_t = new \DateTime();
        $date_t->add(new \DateInterval('P14D'));
        //die("From {$date_f->format('Y-m-d H:m:s')} to {$date_t->format('Y-m-d H:m:s')}");
        $sql = "SELECT * FROM `tblSchedule` WHERE `event_id` = :event_id AND `date` >= :date_f AND `date` <= :date_t";

    }

    public function getPriceBySlotId($slot_id)
    {
        $sql = "SELECT `price` FROM `tblTimeslot` WHERE `id` = :id";
        $placeholders = array(':id' => $slot_id);
        $row = Database::selectQuery($sql, $placeholders, true);
        return $row['price'];
    }

    public function toTime($slot_id)
    {
        $sql = "SELECT DATE_FORMAT(`time`, '%k:%i') AS `time` FROM `tblTimeslot` WHERE `id` = :slot_id";
        $placeholders = array(':slot_id' => $slot_id);
        $row = Database::selectQuery($sql, $placeholders, true);
        return $row['time'];
    }

    public function get($event_id)
    {
        $lang = \wtsd\common\Register::get('lang');
        $date_f = new \DateTime();
        $date_i = new \DateTime();
        $date_t = new \DateTime();
        $date_t->add(new \DateInterval('P14D'));

        $result = [];

        while ($date_i->format('Y-m-d') != $date_t->format('Y-m-d')) {
            $sql = "SELECT 
                    *, DATE_FORMAT(`time`, '%k:%i') AS `time`
                FROM `tblTimeslot` 
                WHERE `status` = 1 AND `wday` = :wday";

            $placeholders = array(':wday' => $date_i->format('N'));
            $rows = Database::selectQuery($sql, $placeholders);

            // check if occupied
            for ($i = 0; $i < count($rows); $i++) {
                if ($this->isAvailable($rows[$i]['id'], $date_i->format('Y-m-d'), $event_id)) {
                    $rows[$i]['status'] = '';
                } else {
                    $rows[$i]['status'] = 'occupied';
                }
            }

            $result[] = array(
                'date_short' => $date_i->format('d.m'),
                'date' => $date_i->format('Y-m-d'),
                'f_name' => $lang['wdays_short'][$date_i->format('N')],
                'wday' => $date_i->format('N'),
                'timetable' => $rows,
                );
            $date_i->add(new \DateInterval('P1D'));
        }

        return $result;
    }

    public function getSlots()
    {
        $sql = "SELECT * FROM `tblTimeslot`";
        return Database::selectQuery($sql);
    }

    public function isAvailable($slot_id, $date, $event_id)
    {
        $sqlStatus = "SELECT count(*) AS `cnt` FROM `tblSchedule` `s` WHERE `date` = :date AND `slot_id` = :slot_id AND `event_id` = :event_id";
        $placeholdersStatus = array(':date' => $date, ':slot_id' => $slot_id, ':event_id' => $event_id);
        $rowStatus = Database::selectQuery($sqlStatus, $placeholdersStatus, true);
        if ($rowStatus['cnt'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function reserve($date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip = '127.0.0.1')
    {
        // @todo: Get current price
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = 'n/a';
        }
        $price = $this->getPriceBySlotId($slot_id);
        $sql = "INSERT INTO `tblSchedule` SET `date` = :date, `slot_id` = :slot_id, `email` = :email, `name` = :name, `tel` = :tel, `comment` = :comment, `price` = :price, `participants` = :participants, `event_id` = :event_id, `ip` = :ip";
        $placeholders = array(':date' => $date, ':slot_id' => $slot_id, ':email' => $email, ':name' => $name,
            ':tel' => $tel, ':comment' => $comment, ':price' => $price, ':participants' => $participants, ':event_id' => $event_id, ':ip' => $ip);
        return Database::insertQuery($sql, $placeholders);

    }

    public function notify($id, $date, $slot_id, $name, $tel, $participants, $comment, $email, $event_id, $ip)
    {
        $config = \wtsd\common\Register::get('config');
        $lang = \wtsd\common\Register::get('lang');
        $event = new \wtsd\event\Event();

        $bookingmail = $config['bookingmail'];

        $errors = [];

        $arr = array(
            'schedule_id' => $id,
            'date' => $date,
            'slot_id' => $slot_id,
            'time' => $this->toTime($slot_id),
            'name' => $name,
            'tel' => $tel,
            'participants' => $participants,
            'comment' => $comment,
            'email' => $email,
            'event_id' => $event_id,
            'event' => $event->loadById($event_id),
            'ip' => $ip,
            );

        $view = new \wtsd\common\Template('default');
        $view->assignAll($arr);

        try {
            $mail = new \PHPMailer();
            $mail->SetFrom($config['noreplymail'], $config['noreplymailname']);
            $mail->addAddress($config['bookingmail']);
            $mail->CharSet = 'UTF-8';

            $mail->Subject = sprintf($lang['booking']['subj-admin'], $date, $this->toTime($slot_id));
            $mail->MsgHTML($view->render('email/booking-notif-admin.tpl'));
            $mail->Send();
        } catch (\phpmailerException $e) {
            $errors[] = 'admin mail error [' . $id . ']: ' . $e->errorMessage();
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $mailClient = new \PHPMailer();
                $mailClient->SetFrom($config['noreplymail'], $config['noreplymailname']);
                $mailClient->AddReplyTo($config['replymail'], $config['replymailname']);
                $mailClient->addAddress($email);
                $mailClient->CharSet = 'UTF-8';

                $mailClient->Subject = sprintf($lang['booking']['subj-user'], $date, $this->toTime($slot_id));
                $mailClient->MsgHTML($view->render('email/booking-notif-client.tpl'));
                $mailClient->Send();
            } catch (\phpmailerException $e) {
                $errors[] = 'client mail error [' . $id . ']: ' . $e->errorMessage();
            }
        } else {
            $errors[] = 'no email for ' . $id;
        }

        if (count($errors) > 0) {
            \wtsd\common\Log::write('mail', $errors);
            return false;
        }
        return true;

    }

    public function getStatisticsAll()
    {
        $sqlAll = "SELECT count(*) AS `cnt` FROM `tblSchedule` WHERE `status` = 1";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['cnt'];
    }

    public function getStatisticsMonth()
    {
        $sqlAll = "SELECT count(*) AS `cnt` FROM `tblSchedule` WHERE `status` = 1 AND Month(`date`) = Month(Curdate())";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['cnt'];
    }
    public function getStatisticsMonthPrev()
    {
        $sqlAll = "SELECT count(*) AS `cnt` FROM `tblSchedule` WHERE `status` = 1 AND Month(`date`) = Month(Curdate()) - 1";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['cnt'];
    }


    public function getStatisticsAllMoney()
    {
        $sqlAll = "SELECT sum(`price`) AS `sum` FROM `tblSchedule` WHERE `status` = 1";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['sum'];
    }

    public function getStatisticsMonthMoney()
    {
        $sqlAll = "SELECT sum(`price`) AS `sum` FROM `tblSchedule` WHERE `status` = 1 AND Month(`date`) = Month(Curdate())";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['sum'];
    }
    public function getStatisticsMonthPrevMoney()
    {
        $sqlAll = "SELECT sum(`price`) AS `sum` FROM `tblSchedule` WHERE `status` = 1 AND Month(`date`) = Month(Curdate()) - 1";
        $row = Database::selectQuery($sqlAll, null, true);
        return $row['sum'];
    }

    public function getWinners($event_id, $limit = 20)
    {
        $sql = "SELECT * FROM `tblSchedule` WHERE `event_id` = :event_id AND `status` = 1 AND `date` < Now() AND `score` > 0 ORDER BY `score` ASC";
        return Database::selectQuery($sql, array(':event_id' => $event_id));
    }
}