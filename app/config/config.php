<?php

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
|
| Here you can setup the phalcon configuration variables
| Just add an array such as 
| 
| 'my_config' => [
|
|     'my_variable'     => 'my_value',
|
| ],
|
| After that you can access your variable via
|
| $config->my_config->my_variable
| or $this->my_config->my_variable in an object context
|
| From anywhere
|
|--------------------------------------------------------------------------
| For more details about the config system : 
| @link http://docs.phalconphp.com/en/latest/api/Phalcon_Config.html
|
*/

return new \Phalcon\Config([

    'database' => [

        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => '',
        'charset'     => 'utf8'
    ],

    'application' => [

        'controllersDir' => __DIR__  . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'librariesDir'   => __DIR__ . '/../../app/libraries/',
        'viewsDir'       => __DIR__ . '/../../app/views/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '',
    ],

    'autoload' => [

        'configs' => []

    ]

]);
