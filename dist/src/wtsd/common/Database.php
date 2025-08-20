<?php
namespace wtsd\common;

use PDO;
use PDOException;
use wtsd\common;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Database
{
    public static $instance = null;

    protected static $logInfo = [];
    
    public static function getInstance($instanceId = 0)
    {
        if (null === self::$instance) {
            try {

                $configFile = 'database-'.strtolower(AppKernel::getEnvironment());
                $db = Register::get($configFile);

                if (!$db) {
                    $msgErr = sprintf('Database is not configured for %s environment', AppKernel::getEnvironment());
                    throw new \Exception($msgErr);
                }
                // For sharding purposes
                if ($instanceId > 0) {
                    $db = $db[$instanceId];
                }

                $configObj = new \Doctrine\DBAL\Configuration();
                self::$instance = \Doctrine\DBAL\DriverManager::getConnection($db, $configObj);
                if (!self::$instance) {
                    throw new \PDOException();
                }
            } catch (\Exception $e) {
                self::error($e->getMessage(), true);
            } catch (\PDOException $e) {
                self::error($e->getMessage(), true);
                die();
            }
        }
        return self::$instance;
    }

    static public function getQueryBuilder()
    {
        self::getInstance();
        return self::$instance->createQueryBuilder();
    }

    static public function lastId()
    {
        return self::$instance->lastInsertId();
    }
    
    static public function select($sql, $placeholders = null, $single = false, $isBinding = false)
    {
        if ($isBinding) {
            return self::selectQueryBind($sql, $placeholders, $single);
        } else {
            return self::selectQuery($sql, $placeholders, $single);
        }
    }

    static public function selectQuery($sql, $placeholders = null, $single = false)
    {
        try {
            self::getInstance();
            $stmt = self::$instance->prepare($sql);
            $stmt->execute($placeholders);

            self::debug($sql, $placeholders);
            if ($single) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            if ($e->getCode() !== '42000') {
                self::error($e->getMessage());
            }
        }
    }

    static public function selectQueryBind($sql, $placeholders = null, $single = false)
    {
        try {
            self::getInstance();
            $stmt = self::$instance->prepare($sql);
            if (count($placeholders) > 0) {
                foreach ($placeholders as $name => $arr) {
                    if ($arr['type'] == 'int') {
                        $type = \PDO::PARAM_INT;
                    } else {
                        $type = \PDO::PARAM_STR;
                    }
                    $stmt->bindParam($name, $arr['value'], $type);
                }
            }
            
            self::debug($sql, $placeholders);
            $stmt->execute();
            if ($single) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            self::error($e->getMessage());
        }
    }

    static public function update($sql, $placeholders = null) {
        return self::updateQuery($sql, $placeholders);
    }

    static public function updateQuery($sql, $placeholders = null)
    {
        try {
            self::getInstance();
            $stmt = self::$instance->prepare($sql);

            self::debug($sql, $placeholders);
            $stmt->execute($placeholders);
            return true;
        } catch (\PDOException $e) {
            self::error($e->getMessage());
        }
    }

    static public function insert($sql, $placeholders = null)
    {
        return self::insertQuery($sql, $placeholders);
    }

    static public function insertQuery($sql, $placeholders = null)
    {
        try {
            self::getInstance();
            $stmt = self::$instance->prepare($sql);

            self::debug($sql, $placeholders);
            $stmt->execute($placeholders);
            return self::$instance->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                return 0;
            } else {
                self::error($e->getMessage());
            }
        }
    }

    static public function delete($sql, $placeholders = null)
    {
        return self::deleteQuery($sql, $placeholders);
    }
    
    static public function deleteQuery($sql, $placeholders = null)
    {
        try {
            self::getInstance();
            $stmt = self::$instance->prepare($sql);

            self::debug($sql, $placeholders);
            $stmt->execute($placeholders);
            return $stmt->rowCount();
            //return self::$instance->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                return 0;
            } else {
                self::error($e->getMessage());
            }
        }
    }
    
    static public function error($msg, $doEcho = false)
    {

        Log::write('db-err', array($msg, $_SERVER['REMOTE_ADDR']));

        echo $msg;
        die();
    }

    static protected function debug($sql, $placeholders = null, $time = 0)
    {
        self::$logInfo[] = array(
            'query' => $sql,
            'values' => json_encode($placeholders),
            'date' => date("Y-m-d H:i:s"),
            'time' => $time,
            );
    }

    static public function showDebugInfo()
    {
        return self::$logInfo;
    }
}
