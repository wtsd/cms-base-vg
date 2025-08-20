<?php
namespace wtsd\common;

use wtsd\common;
use Symfony\Component\Yaml\Parser;
/**
* Basic class for the fundamental site functionality according to the
* configuration.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.3.1
*/
class Register
{

    protected static $register = [];

    private static $files;
    private static $instance = null;
    
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    protected static function includeFile($path, $type)
    {
        if (!file_exists($path)) {
            throw new \Exception('No configured ' . $path . ' file');
        } else {
            $yaml = new Parser();
            try {
                self::$register[$type] = $yaml->parse(file_get_contents($path));
                return self::$register[$type];
            } catch (\Exception $e) {
                echo sprintf("Unable to parse the YAML string: %s :: %s", $path, $e->getMessage());
            } catch (\Symfony\Component\Yaml\ParseException $e) {
                echo sprintf("Unable to parse the YAML string: %s :: %s", $path, $e->getMessage());
            }
        }
    }

    public function __construct()
    {
        $this->confDir = ROOT . '/app/config/';
        
        $language = 'ru';
        $this->langDir = ROOT . '/app/languages/';

        self::$files['config'] = $this->confDir .'config.yml';
        self::includeFile(self::$files['config'], 'config');
        
        self::$files['database-dev'] = $this->confDir . 'database-dev.yml';
        self::$files['database-production'] = $this->confDir . 'database-production.yml';
        self::$files['database-stage'] = $this->confDir . 'database-stage.yml';
        self::$files['routing'] = $this->confDir . 'routing.yml';
        

        // Social
        self::$files['google'] = $this->confDir . 'google-prod.yml';
        self::$files['google-dev'] = $this->confDir . 'google-dev.yml';
        self::$files['instagram'] = $this->confDir . 'instagram.yml';
        self::$files['twitter'] = $this->confDir . 'twitter.yml';
        self::$files['vk'] = $this->confDir . 'vk.yml';
        self::$files['yandex'] = $this->confDir . 'yandex.yml';

        // Language
        self::$files['lang'] = $this->langDir . $language .'.yml';
        
    }

    static public function get($type, $field = null)
    { 
        self::getInstance();
        if (isset(self::$register[$type])) {
            if ($field) {
                if (isset(self::$register[$type][$field])) {
                    return self::$register[$type][$field];
                }
                return null;
            }
            return self::$register[$type];
        }
        try {
            if ($field) {
                return self::includeFile(self::$files[$type], $type)[$field];
            }
            return self::includeFile(self::$files[$type], $type);
        } catch (\Exception $e) {
            \wtsd\common\Log::write('common', [$e->getMessage()]);
        }
    }

    static public function set($type, $field, $value)
    {
        self::getInstance();
        self::$register[$type][$field] = $value;
    }

    public static function error($msg)
    {
        echo $msg;
        exit();
    }
}
