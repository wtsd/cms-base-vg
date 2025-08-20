#!/usr/local/bin/php
<?php


define('ROOT', dirname(dirname(__FILE__)));

if (in_array(strtolower(readline('Do you want to add a new host? (Y/n) ')), ['y', ''])) {
    echo 'Adding a new host.'.PHP_EOL;
    exec('sudo php '.ROOT.'/scripts/add-host.php');
}

if (in_array(strtolower(readline('Do you want to install database? (Y/n) ')), ['y', ''])) {
    echo 'Adding a database.'.PHP_EOL;
    exec('php '.ROOT.'/scripts/add-database.php');
}

if (in_array(strtolower(readline('Do you want to install composer dependencies? (Y/n) ')), ['y', ''])) {
    echo 'Installing dependencies.'.PHP_EOL;
    exec('cd '.ROOT.'/dist && composer install');
}

if (in_array(strtolower(readline('Do you want to install bower dependencies? (Y/n) ')), ['y', ''])) {
    echo 'Fetching resources.'.PHP_EOL;
    exec('cd '.ROOT.'/dist && bower install');
}

function fillConfig(array $config, array $final)
{
    foreach ($config as $field => $value) {

        if (is_array($value)) {
            $final[$field] = fillConfig($value, $final);
        }

        if (!is_array($field)) {
            $newValue = readline('Value for "'.$field.'" (default: "'.$value.'"): ') ;
            if ($newValue != '') {
                $final[$field] = $newValue;
            } else {
                $final[$field] = $value;
            }
        }
        
    }
    return $final;
}

if (in_array(strtolower(readline('Do you want to configure the project? (Y/n) ')), ['y', ''])) {

    include(ROOT.'/dist/vendor/autoload.php');

    $yaml = new \Symfony\Component\Yaml\Parser();

    try {
        $configDist = $yaml->parse(file_get_contents(ROOT.'/dist/app/config/config-dist.yml'));
    } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
        printf("Unable to parse the YAML string: %s", $e->getMessage());
    }

    $final = [];
    $final = fillConfig($configDist, $final);


    $dumper = new \Symfony\Component\Yaml\Dumper();

    $data = $dumper->dump($final);
    var_dump($data);
    //file_put_contents('/path/to/file.yml', $yaml);
}