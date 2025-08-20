#!/usr/local/bin/php
<?php
define('ROOT', dirname(dirname(__FILE__)));
class AddDatabase
{
    protected $db = array();
    protected $dbh = null;

    protected $structPath;
    protected $userPath;
    protected $catPath;

    protected $newdB = array();

    public function setOptions(array $options = null)
    {
        ini_set('memory_limit', '5120M');
        ini_set('database.params.charset', '"utf8"');
        set_time_limit(0);

        if ($options === null) {

            if (isset($this->newdB['host'])) {
                $this->db['host'] = $this->newdB['host'];
            } else {
                $this->db['host'] = '127.0.0.1';
            }

            if (isset($this->newdB['user'])) {
                $this->db['user'] = $this->newdB['user'];
            } else {
                $this->db['user'] = 'root';
            }

            if (isset($this->newdB['pass'])) {
                $this->db['password'] = $this->newdB['pass'];
            } else {
                $this->db['password'] = '';
            }
            
        } else {
            $this->db = $options;
        }
    }

    public function initDatabase()
    {
        $pdoOptions = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, 
                \PDO::ATTR_PERSISTENT => false
            );
        $this->dbh = new \PDO('mysql:host=' . $this->db['host'], $this->db['user'], $this->db['password'], $pdoOptions);
        if (!$this->dbh) {
            throw new \PDOException();
        }

        $this->dbh->query("SET NAMES 'utf8'");
        $this->dbh->query('SET time_zone = "+04:00"');
    }

    public function createDatabase($newDb, $newUser = '', $newPassword = '')
    {

        $this->dbh->exec("CREATE DATABASE IF NOT EXISTS `$newDb` CHARACTER SET utf8 COLLATE utf8_general_ci");
        //$this->dbh->exec("CREATE USER '$newUser'@'localhost' IDENTIFIED BY '$newPassword'; GRANT ALL ON `$newPassword`.* TO '$newUser'@'localhost'; FLUSH PRIVILEGES;");
    }

    public function runFile($fname)
    {
        $sql = file_get_contents($fname);
        $this->dbh->exec($sql);
    }

    public function addCustomUser()
    {
        $user = readline('Username: ');
        $f_name = readline('Full name: ');
        $password_unhashed = readline('Password: ');
        $email = readline('Email: ');
        $tel = readline('Tel: ');
        $key = md5(date('now') . $email . 'Crypto');

        $sql = "INSERT INTO `tblUser` (`name`, `passwd`, `f_name`, `status`, `mdate`, `cdate`, `email`, `roles`, `key`, `tel`) VALUES (:user, :passwd, :f_name, 1, Now(), Now(), :email, 'ROLE_ADMIN', :key, :tel)";
        $placeholders = [':user' => $user, ':passwd' => password_hash($password_unhashed, CRYPT_BLOWFISH), ':f_name' => $f_name, ':email' => $email, ':key' => $key, ':tel' => $tel];

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($placeholders);
        echo 'User is added!' . PHP_EOL;
    }

    public function run()
    {
        try {
            if (in_array(strtolower(readline('Do you want to create a database? (Y/n) ')), ['y', ''])) {
                echo sprintf('Creating Database "%s"…', $this->newdB['name']);
                $this->createDatabase($this->newdB['name']/*, $this->newdB['user'], $this->newdB['pass']*/);
                echo ' done!'.PHP_EOL;
            }

            $this->dbh->query("USE " . $this->newdB['name']);
            if (!$this->dbh) {
                throw new \PDOException();
            }

            if (in_array(strtolower(readline('Do you want to create a database structure? (Y/n) ')), ['y', ''])) {
                echo 'Creating Database structure…';
                $this->runFile($this->structPath);
                echo ' done!'.PHP_EOL;
            }

            if (strtolower(readline('Do you want to add a custom user? (Y/n) ')) == 'y') {
                $this->addCustomUser();
            } elseif (strtolower(readline('Do you want to add a standard user? (Y/n) ')) == 'y') {
                $this->runFile($this->userPath);
            }

            if (in_array(strtolower(readline('Generate a config file? (Y/n) ')), ['y', ''])) {
                $env = readline('Environment: (dev/prod) ');
                if (in_array($env, ['dev', 'prod'])) {
                    $filename = ROOT . '/dist/app/config/database-'.$env.'.yml';
                    $contents_tpl = 'driver: pdo_mysql
host: %s
user: %s
password: %s
dbname: %s
charset: utf8
';
                    $data = sprintf($contents_tpl, $host, $user, $pass, $name);

                    file_put_contents($filename, $data);
                }

            }

        } catch (\PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    }

    public function copyConfig()
    {
        $sampleConfPath = $this->configPath . 'database-dist.php';
        $devConfPath = $this->configPath . 'database-dev.php';
        $prodConfPath = $this->configPath . 'database-prod.php';
    }

    public function __construct($name = '', $user = '', $pass = '', $host= '', $engine = 'pdo_mysql')
    {
            $this->newdB['name'] = $name;

            if ($user) {
                $this->newdB['user'] = $user;
            }
            
            if ($pass) {
                $this->newdB['pass'] = $pass;
            }

            if ($host) {
                $this->newdB['host'] = $host;
            }

            $this->configPath = ROOT . '/dist/app/config/';
            $this->structPath = ROOT . '/db/db-struct.sql';
            $this->userPath = ROOT . '/db/add-user.sql';
            $this->catPath = ROOT . '/db/add-categories.sql';

            $this->setOptions();
            echo 'Testing DB connection and setting options…';
            $this->initDatabase();
            echo ' done!'.PHP_EOL;

    }


}

    $name = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : readline('Database name: ');
    $user = isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : 'root';
    $pass = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : '';
    $host = isset($_SERVER['argv'][4]) ? $_SERVER['argv'][4] : 'localhost';

    if ($name == '') {
        die('Aborted!'.PHP_EOL);
    }

    $script = new AddDatabase($name, $user, $pass, $host);
    $script->run();

    echo '=============== MySQL ==============='.PHP_EOL;
    echo ' name: ' . $name . PHP_EOL;
    echo ' user: ' . $user . PHP_EOL;
    echo ' pass: ' . $pass . PHP_EOL;
    echo ' host: ' . $host . PHP_EOL;
    echo '====================================='.PHP_EOL;

