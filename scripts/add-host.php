#!/usr/local/bin/php
<?php

class AddHost
{
    private $_conf = array(
            'userdir' => '/var/www/',
            'directories' => array('logs', 'tmp'),
            'apachedir' => '/etc/apache2/',
            'hosts' => '/etc/hosts',
            'apache-available-template' => '<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName %s.dev

        DocumentRoot /var/www/%s
        <Directory />
                Options FollowSymLinks
                AllowOverride All
        </Directory>
        <Directory /var/www/%s>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

        ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
        <Directory "/usr/lib/cgi-bin">
                AllowOverride All
                Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
                Order allow,deny
                Allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
',
            'apache-enabled-template' => '<VirtualHost *:80>
    ServerName %s
    ServerAlias www.%s

    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/%s/public_html

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory /var/www/%s/public_html>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
        Order allow,deny
        allow from all
        </Directory>
</VirtualHost>',
        );

    private $_hostname = '';
    private $_newId = 0;
    private $_sourceDir = '';

    private function writeConfig($configFile, $text, $writeMode = 'w')
    {
        $handler = fopen($configFile, $writeMode);
        fwrite($handler, $text);
        @fclose($handler);
    }

    private function getHostname()
    {
        if (count($_SERVER['argv']) > 1) {
            return $_SERVER['argv'][1];
        }
        return readline('Hostname (i.e. example.dev): ');
    }

    private function createDirectories()
    {
        // Creating directories
        foreach ($this->_conf['directories'] as $directory) {
            $absoluteDir = $this->_conf['userdir'] . $this->_hostname . '/' . $directory;
            if (!file_exists($absoluteDir)) {
                mkdir($absoluteDir, 0777, true);
            } elseif (!is_dir($absoluteDir)) {
                $this->error('Not a directory! ' . $absoluteDir);
            }
        }
        exec('ln -s ' . $this->_sourceDir . ' ' . $this->_conf['userdir'] . $this->_hostname . '/public_html');
    }

    private function _getNewId()
    {
        exec('ls -1 ' . $this->_conf['apachedir'] . 'sites-available/[0-9]*', $result);
        $lastId = intval(array_shift(explode('-', array_pop(explode('/', array_pop($result))))));
        $newId = $lastId + 1;

        return $newId;
    }

    private function _writeApacheConfigs($newId)
    {
        $newSAConf = sprintf('%s/sites-available/%03d-%s.conf', $this->_conf['apachedir'], $newId, $this->_hostname);
        $availableConf = sprintf($this->_conf['apache-available-template'], $this->_hostname, $this->_hostname, $this->_hostname, $this->_hostname);
        $this->writeConfig($newSAConf, $availableConf);

        $newSEConf = sprintf('%s/sites-enabled/%03d-%s.conf', $this->_conf['apachedir'], $newId, $this->_hostname);
        $enabledConf = sprintf($this->_conf['apache-enabled-template'], $this->_hostname, $this->_hostname, $this->_hostname, $this->_hostname, $this->_hostname);
        $this->writeConfig($newSEConf, $enabledConf);
    }

    private function writeHosts($hostname)
    {
        $filename = $this->_conf['hosts'];
        $record = sprintf("\n127.0.0.1    %s\n", $hostname);
        $this->writeConfig($filename, $record, 'a');
    }

    private function restartApache()
    {
        exec('service apache2 restart');
    }

    private function error($text)
    {
        die("$text\n");
    }

    private function msg($text)
    {
        echo sprintf("%s: %s\n", date("Y-m-d H:i:s"), $text);
    }

    public function run()
    {
        $user = exec('whoami');
        if ($user != 'root') {
            $this->error('Invalid user. Try `sudo`.');
        }
        $this->_sourceDir = dirname(dirname(__FILE__)) . '/dist/';
        $this->_hostname = $this->getHostname();
        if ($this->_hostname) {
            $this->createDirectories();
            $this->msg('Created user directories.');

            $this->_newId = $this->_getNewId();
            $this->_writeApacheConfigs($this->_newId);
            $this->msg('Configured Apache2.');
            
            $this->writeHosts($this->_hostname);
            $this->msg('Added record to /etc/hosts.');

            $this->msg('Restarting Apache2.');
            $this->restartApache();
            $this->msg('Done!');
        } else {
            $this->error('Invalid hostname');
        }
    }
}

$script = new AddHost();
$script->run();