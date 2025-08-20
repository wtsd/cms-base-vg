<?php
namespace wtsd\user;

use wtsd\common\Database;
use PDO;
/**
* Used as a storage for authentication methods. Later
* may be used as the more complicated auth mechanism.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1.1
*/
class User
{
    const SQL_UPDATE_PROFILE = 'UPDATE `tblUser` SET %s `name` = :name WHERE `id` = :id',
            SQL_AUTHORIZE = "SELECT * FROM `tblUser` WHERE `name` = :uname";

    public $login, $is_authorized;

    protected $name = 'n/a';
    protected $id = 0;
    protected $isAdmin = false;
    protected $table = 'tblUser';

    public static $statuses = array(
        1 => 'Активная',
        0 => 'Заблокирована',
        );

    public static $groups = array(
        1 => 'Администратор',
        0 => 'Пользователь',
        2 => 'Гость',
        );

    public static $hash = 'Crypto';

    protected $foreignKey = 'user_id';

    public function __construct()
    {
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['ckey'])) {
            $ckey = base64_decode($_COOKIE['ckey']);
            $user_id = base64_decode($_COOKIE['user_id']);
            
            $queryBuilder = Database::getQueryBuilder();
            $row = $queryBuilder->select('`u`.*', '`g`.`role`')
                ->from('`tblUser`', '`u`')
                ->leftJoin('`u`', 'tblGroup', 'g', '`u`.`group_id` = `g`.`id`')
                //->innerJoin('`u`', 'tblGroup', 'g', '`g`.`id` = `u`.`group_id`')
                ->where('`u`.`ckey` = ' . $queryBuilder->createNamedParameter($ckey))
                ->andWhere('`u`.`id` = ' . $queryBuilder->createNamedParameter($user_id))
                ->andWhere('`u`.`status` = 1')
                ->andWhere('`u`.`is_deleted` = 0')
                ->execute()
                ->fetch(\PDO::FETCH_ASSOC);

            if (count($row) > 0) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->tel = $row['tel'];
                if ($row['role'] == 'admin') {
                    $this->isAdmin = true;
                }
            }
        }
    }
    
    public function isAuthorized()
    {
        if ($this->id > 0) {
            return true;
        }

        return false;
        //return !(!isset($_SESSION['is_authorized']) || !$_SESSION['is_authorized']);
    }
    
    public function isAdmin()
    {
        return $this->isSuperuser();
    }

    public function isSuperuser()
    {
        return $this->isAdmin;
    }
    
    public function authenticate($email, $passwd)
    {
        $queryBuilder = Database::getQueryBuilder();
        $row = $queryBuilder->select('`u`.*', '`g`.`role`')
                ->from('tblUser', '`u`')
                ->where('`u`.`is_deleted` = 0')
                ->leftJoin('`u`', 'tblGroup', 'g', '`u`.`group_id` = `g`.`id`')
                ->andWhere('`u`.`status` = 1')
                ->andWhere('`u`.`email` = ' . $queryBuilder->createNamedParameter($email))
            ->execute()
            ->fetch(\PDO::FETCH_ASSOC);

        //die(password_hash($passwd));
        if (password_verify($passwd, $row['passwd'])) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->tel = $row['tel'];

            if ($row['role'] == 'admin') {
                $this->isAdmin = true;
            }
            $this->setCookies($row['id'], $row['ckey']);
            return true;
        }
        return false;
    }

    public function logout()
    {

        setcookie("user_id", null, -1, '/');
        setcookie("ckey", null, -1, '/');

        unset($_COOKIE['user_id']);
        unset($_COOKIE['ckey']);
    }

    protected function setCookies($id, $ckey)
    {
        $expires = time() + 86400 * 365 * 2;
        setcookie("user_id", base64_encode($id), $expires, '/');
        setcookie("ckey", base64_encode($ckey), $expires, '/');
    }

    static public function log($uid, $logtype = '')
    {
        Log::write('user-activity', array($_SERVER['REMOTE_ADDR'], 'uid:' . $uid, $logtype));
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTel()
    {
        return $this->tel;
    }

    public function getFullName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function count()
    {

        $queryBuilder = Database::getQueryBuilder();
        $row = $queryBuilder->select('count(*) AS `cnt`')
            ->from('tblUser')
            ->where('`is_deleted` = 0')
            ->execute()
            ->fetch(\PDO::FETCH_ASSOC);
    
        return $row['cnt'];
    }

    public function getAllActive()
    {
        $queryBuilder = Database::getQueryBuilder();
        
        $rows = $queryBuilder->select('`e`.*')
            ->from('tblUser', 'e')
            ->where('`e`.`is_deleted` = 0')
            ->andWhere('`e`.`status` = 1')
            ->andWhere('`e`.`group_id` IN (0, 1)')
            ->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }
    public function getAll($page = 1, $size = 1000, $filter = '')
    {
        $offset = (($page - 1) * $size);

        $queryBuilder = Database::getQueryBuilder();
        
        $queryBuilder->select('`u`.*', '`g`.`name` AS `group_name`')
            ->from('tblUser', 'u')
            ->where('`u`.`is_deleted` = 0')
            ->innerJoin('u', 'tblGroup', 'g', '`g`.`id` = `u`.`group_id`')
            ->setFirstResult($offset)
            ->setMaxResults($size);

        if ($filter != '') {
            $queryBuilder->andWhere('`e`.`name` LIKE :name')->setParameter(':name', "%$filter%");
        }

        $rows = $queryBuilder->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }
    
    public function load($id = null)
    {
        if (!$id && $this->id) {
            $id = $this->id;
        }
      $queryBuilder = Database::getQueryBuilder();
        
        $queryBuilder->select('`e`.*')
            ->from('tblUser', 'e')
            ->where('`is_deleted` = 0')
            ->andWhere('`e`.`id` = '. $queryBuilder->createNamedParameter($id));

        $row = $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);

        if (count($row) == 0) {
            throw new \Exception('User not found!');
        }

        if ($row) {
            foreach ($row as $field => $val) {
                $this->$field = $val;
            }
        }

        return $row;
    }

    public function getArray()
    {
        $result = array();
        foreach ($this as $field => $val) {
            $result[$field] = $val;
        }
        return $result;
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'email' => '',
            'name' => '',
            'tel' => '',
            'descr' => '',
            'status' => '-1',
            'group_id' => '-1',
            );   
    }

    public static function validate($arr)
    {
        $errors = [];
        // Required
        if (isset($arr['id']) && $arr['id'] == 0) {
            if ($arr['passwd'] == '') {
                $errors[] = 'Пароль не может быть пустым.';
            }

            $sqlEmail = "SELECT * FROM `tblUser` WHERE `email` = :email";
            $row = Database::selectQuery($sqlEmail, [':email' => $arr['email']]);
            if (count($row) > 0) {
                $errors[] = 'Почта должна быть уникальной.';
            }
        }
        if (isset($arr['id']) && $arr['id'] > 0) {

            $sqlEmail = "SELECT * FROM `tblUser` WHERE `email` = :email AND `id` != :id";
            $row = Database::selectQuery($sqlEmail, [':email' => $arr['email'], ':id' => $arr['id']]);
            if (count($row) > 0) {
                $errors[] = 'Почта должна быть уникальной.';
            }   
        }
        
        // Types
        if (!filter_var($arr['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неправильный формат почты.';
        }


        return $errors;
    }

    public function save($arr)
    {
        $placeholders = array();
        foreach ($arr as $field => $value) {
            $placeholders[':'.$field] = $value;
        }

        $placeholders = array(
            ':email' => $arr['email'],
            ':name' => $arr['name'],
            ':tel' => $arr['tel'],
            ':descr' => $arr['descr'],
            ':status' => $arr['status'],
            ':group_id' => $arr['group_id'],
            );
        if (isset($arr['passwd']) && $arr['passwd'] != '') {
            $placeholders[':passwd'] = password_hash($arr['passwd'], CRYPT_BLOWFISH);
            $placeholders[':ckey'] = md5(date('now') . $arr['email'] . self::$hash);
        }
        if ($arr['id'] > 0) {
            $this->load($arr['id']);
            $placeholders[':id'] = $arr['id'];
            $this->update($placeholders);
        }
        if ($arr['id'] == 0) {
            $user = new self();
            $placeholders[':user_id'] = $user->getId();
            $placeholders[':ckey'] = md5(date('now') . $arr['email'] . self::$hash);
            $this->insert($placeholders);

            // Send mail notification
            /*
            $config = \wtsd\common\Register::get('config');
            $tplArr = [
                'first_name' => $arr['first_name'],
                'last_name' => $arr['last_name'],
                'email' => $arr['email'],
                'password' => $arr['password'],
                'url' => 'http://'.$config['base_url'].'/',
            ];
            $view = new \wtsd\common\Template($config['template']);
            $view->assignAll($tplArr);
            $html = $view->render('email/new-user-notification.tpl');
        
            try {
                $mail = new \PHPMailer();
                $mail->SetFrom($config['noreplymail'], $config['noreplymailname']);

                $mail->addAddress($tplArr['email']);
            
                //$mail->addAddress($config['adminmail']);
                $mail->CharSet = 'UTF-8';

                $mail->Subject = 'Регистрация в системе';
                $mail->MsgHTML($html);

                $mail->Send();
            } catch (\phpmailerException $e) {
                \wtsd\common\Log::write('mail', 'admin mail error [' . $id . ']: ' . $e->errorMessage());
            }*/

        }

    }

    public function update($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();        
        $queryBuilder->update('tblUser')
            ->where('`id` = '. $queryBuilder->createNamedParameter($placeholders[':id']));

        if (isset($placeholders[':email'])) {
            $queryBuilder->set('`email`', $queryBuilder->createNamedParameter($placeholders[':email']));
        }
        if (isset($placeholders[':name'])) {
            $queryBuilder->set('`name`', $queryBuilder->createNamedParameter($placeholders[':name']));
        }
        if (isset($placeholders[':tel'])) {
            $queryBuilder->set('`tel`', $queryBuilder->createNamedParameter($placeholders[':tel']));
        }
        if (isset($placeholders[':descr'])) {
            $queryBuilder->set('`descr`', $queryBuilder->createNamedParameter($placeholders[':descr']));
        }
        if (isset($placeholders[':status'])) {
            $queryBuilder->set('`status`', $queryBuilder->createNamedParameter($placeholders[':status']));
        }
        if (isset($placeholders[':group_id'])) {
            $queryBuilder->set('`group_id`', $queryBuilder->createNamedParameter($placeholders[':group_id']));
        }


        if (isset($placeholders[':passwd'])) {
            $queryBuilder->set('passwd', $queryBuilder->createNamedParameter($placeholders[':passwd']))
                ->set('ckey', $queryBuilder->createNamedParameter($placeholders[':ckey']))
                ;
        }
        
        $queryBuilder->execute();
    }

    public static function generateKey($email, $hash)
    {
        return md5(date('now') . $email . $hash);
    }

    public function profileUpdate($email, $name, $passwd, $tel)
    {
        $ckey = self::generateKey($email, self::$hash); //md5(date('now') . $email . self::$hash);

        $sql = "UPDATE `tblUser` 
        SET `email` = :email, `ckey` = :ckey,
        `name` = :name, %s `tel` = :tel
        WHERE `id` = :id AND `is_deleted` = 0";

        $placeholders = array(
            ':ckey' => $ckey,
            ':email' => $email,
            ':name' => $name,
            ':tel' => $tel,
            ':id' => $this->getId(),
            );

        if ($passwd != '') {
            $sql = sprintf($sql, ' `passwd` = :passwd, ');
            $placeholders[':passwd'] = password_hash($passwd, CRYPT_BLOWFISH);
        } else {
            $sql = sprintf($sql, '');
        }

        $this->email = $email;
        $this->name = $name;
        $this->tel = $tel;

        Database::updateQuery($sql, $placeholders);
        $this->setCookies($this->getId(), $ckey);

        /*if ($_FILES['userpic']['size'] > 0) {
            $result = $this->upload($_FILES['userpic'], $this->getId(), ROOT.'/img/userpics/');
        }*/
    }

    public function insert($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();        
        $queryBuilder->insert('tblUser')
            ->values(
                [
'`email`' => $queryBuilder->createNamedParameter($placeholders[':email']),
'`name`' => $queryBuilder->createNamedParameter($placeholders[':name']),
'`tel`' => $queryBuilder->createNamedParameter($placeholders[':tel']),
'`descr`' => $queryBuilder->createNamedParameter($placeholders[':descr']),
'`status`' => $queryBuilder->createNamedParameter($placeholders[':status']),
'`group_id`' => $queryBuilder->createNamedParameter($placeholders[':group_id']),
'`user_id`' => $queryBuilder->createNamedParameter($placeholders[':user_id']),
'`passwd`' => $queryBuilder->createNamedParameter($placeholders[':passwd']),
'`ckey`' => $queryBuilder->createNamedParameter($placeholders[':ckey']),
                ]
                )
            ;

        try {
            $queryBuilder->execute();
            return Database::lastId();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete($id = null)
    {
        $sql = "UPDATE `tblUser` SET `is_deleted` = 1 WHERE `id` = :id AND `is_deleted` = 0";
        $placeholders = array(':id' => $this->id);
        if ($id !== null) {
            $placeholders = array(':id' => $id);
        }
        $result = Database::updateQuery($sql, $placeholders);

        return $result;
    }

    public function toggleStatus()
    {
        $sql = "UPDATE `tblUser` SET `status` = :status WHERE `id` = :id AND `is_deleted` = 0";
        $placeholders = array(':id' => $this->id);
        if ($this->status == 1) {
            $placeholders[':status'] = 0;
        } else {
            $placeholders[':status'] = 1;
        }
        $result = Database::updateQuery($sql, $placeholders);

        return $result;
    }

    public function getStatuses()
    {
        return self::$statuses;
    }

    public function getGroups()
    {
        return self::$groups;
    }
    /*
    static public function encrypt($passwd, $sol = '') 
    {
        return password_hash($passwd, CRYPT_BLOWFISH);
        //return md5($passwd);
    }
    
    static public function doAuthorize($uname, $upass, $remember_me)
    {
    
        $placeholders = array(':uname' => $uname);
        $row = Database::selectQuery(self::SQL_AUTHORIZE, $placeholders, true);

        if (!$row || !$row['id']) {
            return false;
        }
    
        if (password_verify($upass, $row['passwd'])) {
            
            // @todo: Use cookie instead of session
            $_SESSION['uid'] = $row['id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['f_name'] = $row['f_name'];
            $_SESSION['class'] = $row['class'];
            $_SESSION['is_authorized'] = true;
            
            return true;

        } else {
            return false;
        }
    }
    
    static public function logout()
    {
        $_SESSION['is_authorized'] = false;
        unset($_SESSION);
        session_destroy();
    }
    
    public function saveProfile($uid, $passwd, $f_name)
    {
        $placeholders = array(
            ':f_name' => $f_name,
            ':id' => $uid,
            );

        $pwd_sql = '';
        if ($passwd != '') {
            $pwd_sql = "`passwd` = :passwd,";
            $placeholders[':passwd'] = self::encrypt($passwd);
        }

        $sql = sprintf(self::SQL_UPDATE_PROFILE, $pwd_sql);
        $result = Database::updateQuery($sql, $placeholders);

        return $result;
    }

    static public function isDebug()
    {
        if (self::isAuthorized()) {
            return true;        
        }
    }

    static public function getFromId($id)
    {
        $sql = "SELECT * FROM `tblUser` WHERE `id` = :id";
        $placeholders = array(':id' => $id);
        return Database::selectQuery($sql, $placeholders, true);
    }*/

}