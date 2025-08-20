<?php
namespace wtsd\misc;

use wtsd\common\Register;
use wtsd\common\Template;
use wtsd\common\Database;
/**
* File manipulation class.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.2
*/
class Feedback
{
    const SQL_LOAD_FEEDBACKS = "SELECT * FROM `tblFeedback` ORDER BY `cdate` DESC LIMIT :offset, :batch",
            SQL_INSERT_FEEDBACK = "INSERT INTO `tblFeedback` SET `ip` = :ip, `title` = :title, `body` = :body, `cdate` = Now(), `status` = 1, `recipient` = :recipient, `sender` = :sender, `comment` = :comment, `additional` = :additional";

    public static $fields = array(
            array('name' => 'name', 'title' => 'Имя', 'type' => 'text', 'required' => true, 'placeholder' => 'Мария Петрова'),
            array('name' => 'email', 'title' => 'E-mail', 'type' => 'text', 'required' => true, 'placeholder' => 'm.petrova@email.tld'),
            array('name' => 'tel', 'title' => 'Телефон для связи', 'type' => 'text', 'required' => false, 'placeholder' => '+7 901 234 56 78'),
            array('name' => 'msg', 'title' => 'Сообщение', 'type' => 'textarea', 'required' => true, 'placeholder' => ''),
        );

    public function __construct($arr = null)
    {
        $config = Register::get('lang');
        if (count($config['feedback']) > 0) {
            self::$fields = $config['feedback'];
        } elseif (is_array($arr) && count($arr) > 0) {
            self::$fields = $arr;
        }
    }

    public function getForm()
    {
        $_SESSION['token'] = self::generateToken();

        $arr = array(
            'token' => $_SESSION['token'],
            'fields' => self::$fields
            );
        return $arr;
    }

    function getFormAjax()
    {
        $arr = $this->getForm();
        $view = new Template();
        $view->assignAll($arr);
        $html = $view->render('includes/feedback-form.tpl');

        return $html;
    }

    public function getList($page = 1)
    {
        $batch = 100;
        $offset = 0;
        if (intval($page) != 0) {
            $offset = (intval($page) - 1) * $batch;
        }

        $placeholders = array(
            ':batch' => array('type' => 'int', 'value' => $batch),
            ':offset' => array('type' => 'int', 'value' => $offset),
            );
        $records = Database::selectQueryBind(self::SQL_LOAD_FEEDBACKS, $placeholders);
        
        return $records;
    }

    public static function getFields()
    {
        return self::$fields;
    }

    public function doSend($token, $name, $email, $msg)
    {
        $config = Register::get('config');
        $labels = Register::get('lang');
        
        if ($token !== $_SESSION['token']) {
            return json_encode(array('ok' => 0, 'msg' => 'Внутренняя ошибка, попробуйте позже!'));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(array('ok' => 0, 'msg' => 'Неправильно написан email'));
        }

        if ($name == '') {
            return json_encode(array('ok' => 0, 'msg' => 'Поле «' . $field['title'] . '» не заполнено.' . $arr[$name]));
        }

        $arr = array(
            'site' => $labels['site'],
            'arr' => array('name' => $name, 'email' => $email, 'msg' => $msg),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'date' => date('r'),
            'fields' => self::$fields
            );
        
        $view = new Template('default');
        $view->assignAll($arr);
        $body = $view->render('email/feedback-notif-admin.tpl');

        $placeholders = array(
            ':ip' => $_SERVER['REMOTE_ADDR'],
            ':title' => $labels['subject'],
            ':body' => $body,
            ':recipient' => $config['adminmail'],
            ':sender' => $config['noreplymail'],
            ':comment' => 'Sending via email',
            ':additional' => ''
            );
        $newId = Database::insertQuery(self::SQL_INSERT_FEEDBACK, $placeholders);


        try {

            $mail = new \PHPMailer();
            $mail->From = $config['noreplymail'];
            $mail->addAddress($config['adminmail']);
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->CharSet = 'UTF-8';
            $mail->Subject = $labels['subject'];
            $mail->Body    = $body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                session_destroy();
                return array('status' => 'ok', 'msg' => 'Спасибо! Ваше сообщение благополучно отправлено!');
            } else {
                return array('status' => 'error', 'msg' => 'Произошла ошибка при отправке. Попробуйте позже!',
                    'error' => $mail->ErrorInfo);
            }
        } catch (\phpmailerException $e) {
            return [
            'status' => 'error',
            'msg' => 'Произошла ошибка при отправке. Попробуйте позже!',
            'error' => $e->errorMessage(),
            ];
        }
    }

    static public function generateToken()
    {
        $config = Register::get('config');
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = md5('mielophone14 ' . uniqid());
        }
        return $_SESSION['token'];
    }

    static public function requestCall($name, $tel, $ip)
    {
        $labels = Register::get('lang');
        $config = Register::get('config');

        $arr = array(
            'site' => $labels['site'],
            'name' => $name,
            'tel' => $tel,
            'ip' => $ip,
            'date' => date('r'),
            );

        $view = new Template($config['template']);
        $view->assignAll($arr);
        $body = $view->render('email/callback-notif-admin.tpl');

        $sql = "INSERT INTO `tblCallRequest` SET `name` = :name, `tel` = :tel, `ip` = :ip, `cdate` = Now()";
        $placeholders = array(
            ':name' => $name,
            ':tel' => $tel,
            ':ip' => $ip,
            );
        $newId = Database::insertQuery($sql, $placeholders);


        $mail = new \PHPMailer();
        $mail->From = $config['noreplymail'];
        $mail->addAddress($config['adminmail']);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->CharSet = 'UTF-8';
        $mail->Subject = $labels['subject-callback'];
        $mail->Body    = $body;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    }
}
