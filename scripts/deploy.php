#!/usr/local/bin/php
<?php

// sudo apt-get install libssh2-php
class Deployment
{
    protected $host,
                $login,
                $pass;

    public function __construct($host, $login, $pass)
    {
        $this->host = $host;
        $this->login = $login;
        $this->pass = $pass;
    }

    public function connect($port = 22)
    {
        $this->connection = ssh2_connect($this->host, $port);
        ssh2_auth_password($this->connection, $this->login, $this->pass);

        //$sftp = ssh2_sftp($this->connection);

        
    }

    public function sendFile($from, $to)
    {
        ssh2_scp_send($this->connection, $from, $to, 0644);
    }

    public function createDir($dirname, $mode = 0777, $recursive = true)
    {
        ssh2_sftp_mkdir($this->connection, $dirname, $mode, $recursive);
    }
}

define('ROOT', dirname(__DIR__));

$host = '';
$username = '';
$passwod = '';
$remoteRoot = '';

$deploy = new Deployment($host, $username, $password);
$deploy->connect();
$deploy->sendFile(ROOT . '/README.md', $remoteRoot . '/README.md');
