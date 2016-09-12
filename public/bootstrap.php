<?php if ( ! defined('ENVIRONMENT')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Phalcon Debug
|--------------------------------------------------------------------------
|
| When you are running with "development" mode, we load the Debug system
|
| @link http://docs.phalconphp.com/en/latest/reference/debug.html#debug-component
|
*/
if (ENVIRONMENT === 'development')
{
    // Run debug class
    (new \Phalcon\Debug)->listen();
}

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
|
| When you develop, it's really important to get an easy access of your
| Base path and App paths
|
| We will guess for you the BasePath and AppPath
|
*/

// Guess base path
$dir = str_replace('\\', '/', __DIR__);
$basePath = str_replace('public', '', $dir);

// Define  constants
define('BASEPATH', $basePath);
define('APPPATH', BASEPATH . 'app/');


/*
|--------------------------------------------------------------------------
| Magic config
|--------------------------------------------------------------------------
|
| A simple system to fetch the app/config/config.php file and autoload 
| your custom config files added in the app/config/config.php into the 
| array "autoload => configs"
|
| @link http://docs.phalconphp.com/en/latest/api/Phalcon_Config.html
|
*/

// Init config var
$config = new \Phalcon\Config([]);

// Load config file of system
$tmp = require(APPPATH . 'config/config.php');

// Merge with the init config
$config->merge($tmp);

// Load custom config from autoload
if (isset($config->get('autoload')['configs']))
{
    $autoloadConfigs = $config->get('autoload')['configs'];

    if ( ! empty($autoloadConfigs))
    {
        foreach ($autoloadConfigs as $configToLoad)
        {
            if ( ! empty($configToLoad))
            {
                $tmp = require(APPPATH . 'config/' . $configToLoad . '.php');

                $config->merge($tmp);
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
|
| We start the loader class of Phalcon and include the app/config/loader file
| to fetch datas.
|
| @link http://docs.phalconphp.com/en/latest/reference/loader.html
|
*/

// Register an autoloader
$loader = new \Phalcon\Loader();

// Fetch datas
require(APPPATH . 'config/loader.php');

// Register config
$loader->register();


/*
|--------------------------------------------------------------------------
| Dependency injection
|--------------------------------------------------------------------------
|
| Load DI, fetch datas, and run.
|
| Note : If the developer let blank the baseUri we will auto detect the 
| baseUrl to fit with his configuration. It's too a little tip to get
| a full URL and not a simple URI for links generated.
|
| @link http://docs.phalconphp.com/en/latest/api/Phalcon_DI.html
|
*/
$di = new Phalcon\DI\FactoryDefault();

// Fetch baseUri from app/config/config.php
if (isset($config->get('app')['baseUrl']))
{
    $uri = $config->get('app')['baseUrl'];

    if ( ! empty($uri))
    {
        $baseUrl = $uri;
    }

}

// If baseUri it's blank, we will auto guess
if ( ! isset($baseUrl))
{
    // Guess base uri
    $baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
    $baseUrl .= "://".$_SERVER['HTTP_HOST'];
    $baseUrl .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
    $baseUrl = str_replace('public/', '', $baseUrl);
}

// Fetch datas
require(APPPATH . 'config/di.php');

// Connect to the queue
$queue = new Phalcon\Queue\Beanstalk(
    array(
        'host' => '127.0.0.1',
        'port' => '11300'
    )
);

const DEFAULT_URL = 'https://pval-762a5.firebaseio.com/';
const DEFAULT_TOKEN = 'FCNtNVoEbEgeaEVYY3BS8XgtS1n2B3BBpZcYtVZx';
const DEFAULT_PATH = '/firebase/example';
$application = new \Phalcon\Mvc\Application($di);

// Output !
echo $application->handle()->getContent();



