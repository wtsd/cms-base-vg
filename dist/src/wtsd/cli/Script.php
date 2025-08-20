<?php
namespace wtsd\cli;

/**
 * Script running class for dispatching and controlling the
 * script flow. Used by sub-classes extending current
 * class.
 *
 * IMPORTANT! Works with console.php file, which registers
 * autoloaders, includes configuration files, sets environment
 * and so on.
 *
 * Usage:
 * $ /usr/local/bin/php console.php ScriptName [arg1 [arg2 ...]]
 * 
 * @author      Vlad Gafurov <warlockfx@gmail.com>
 * @version     0.1
 * @package     Common
  */
abstract class Script
{
    /**
     * Script name, which is used as an argument for running and
     * is the same as a class name.
     * @var string
     */
    protected $_name;

    /**
     * Full path to dispatcher (console.php) script.
     * @var string
     */
    protected $_dispatcherPath;

    /**
     * Number of maximum allowed concurrent instances of this script.
     * @var int
     */
    protected $_maxinstances = 1;

    /**
     * Process ID of the script.
     * @var int
     */
    protected $_pid;

    /**
     * Arguments, used by user.
     * @var array
     */
    protected $_argv = [];

    /**
     * Constructor.
     */
    public function __construct($argv)
    {
        // $argv = array_map($argv, escapeshellarg);
        $this->_dispatcherPath = array_shift($argv);

        $this->_name = array_shift($argv);
        $this->_argv = $argv;
        $this->_pid = getmypid();
    }

    /**
     * Main method for the extended scripts body. Needs
     * implementation!
     */
    abstract protected function _start();

    /**
     * The main method which runs the routine of
     * pre-checks, script body and post-processes.
     * 
     */
    public function run()
    {
        if ($this->_checkInstances()) {
            $output = $this->_preDispatch();
            $output .= $this->_start();
            $output .= $this->_postDispatch();
        } else {
            $output = 'Exceeding already running processes number.' . PHP_EOL;
        }
        echo $output;
    }

    /**
     * Pre-running routine.
     *
     * @return string
     */
    protected function _preDispatch()
    {
        //@todo: Add routine checks and environmental changes
        return '';
    }

    /**
     * Calculates the memory and time consumption. Possible to
     * add some environmental resetting and other procedures.
     *
     * @return string
     */
    protected function _postDispatch()
    {
        $time   = microtime(true) - DEBUG_TIME_START;
        $memory = memory_get_usage() - DEBUG_MEMORY_START;

        return PHP_EOL . 'Memory consumption: ' . $memory . PHP_EOL .
            'Execution time: ' . $time . PHP_EOL;
    }

    /**
     * Instances of the same scripts are being checked
     * for avoiding too many concurrent runnings.
     * 
     * @return  bool
     */
    protected function _checkInstances()
    {
        $grepPath = '/bin/grep';
        $psPath = 'ps';
        $cmd = $this->_dispatcherPath . ' ' . $this->_name . ' ' . (empty($this->_argv) ? '' : implode($this->_argv, ' ')) . "\b";
        $command = "export COLUMNS=500 ; {$psPath} aux | {$grepPath} '{$cmd}' | {$grepPath} -v '[sudo|grep|su -m|csh -c]'";

        exec($command, $output);

        if (count($output) > $this->_maxinstances) {
            return false;
        }

        return true;
    }

}