<?php

/*
|--------------------------------------------------------------------------
| Config
|--------------------------------------------------------------------------
|
| When bootstrap is running, it creates a configuration variable.
| So we're using $config to keep datas, you can make whatever you want with this.
| 
*/
$di->set('config', function() use ($config) {

	return $config;

});

/*
|--------------------------------------------------------------------------
| Url
|--------------------------------------------------------------------------
|
| Bootstrap.php guess the baseUrl if you haven't filled the baseUri within
| app/config/config.php.
| 
*/
$di->set('url', function() use ($baseUrl) {

	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri($baseUrl);


	return $url;
});

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
|
| Set our database as service, we use config/config.php for this.
| 
*/
$di->set('db', function() use ($config) {

    // Dynamic namespace/class
    $dbClass = 'Phalcon\\Db\\Adapter\\Pdo\\' . $config->database->adapter;

    // Run !
    return new $dbClass(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset
        ));

});

$di->set('mongo', function () {
    $mongo = new Mongo();
    return $mongo->selectDB("pval");
}, true);

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
}, true);

/*
|--------------------------------------------------------------------------
| Volt Service
|--------------------------------------------------------------------------
|
| Volt service (smart template engine) will be enabled
| 
*/
$di->set('voltService', function($view, $di) use ($config) {

  // Init volt engine
	$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

  // Set options to compiler
  // All volt template are compiled within app/cache/volt
	$volt->setOptions(array(

		'compiledPath' => $config->application->cacheDir . 'volt/',
		'compiledExtension' => '.volt-compiled'

		));

  // We require config/volt.php to add some things in our Volt service
  require(APPPATH . '/config/volt.php');


	return $volt;

});

/*
|--------------------------------------------------------------------------
| View
|--------------------------------------------------------------------------
|
| We initialize the view service
| 
*/
$di->set('view', function() use ($config) {

    // Init view class
	$view = new \Phalcon\Mvc\View();

    // Use app/views as dir
	$view->setViewsDir($config->application->viewsDir);

    // We want use volt with .volt extension
	$view->registerEngines(array(
		'.volt' => 'voltService'
		));

	return $view;

});


/*
|--------------------------------------------------------------------------
| Router 
|--------------------------------------------------------------------------
|
| We initialize the Phalcon router, it requires the routes and runs it
| 
*/
$di->set('router', function() {

    // Init router
    $router = new Phalcon\Mvc\Router();

    // Use $_SERVER['REQUEST_URI'] (NGINX)
    if (!isset($_GET['_url']))
    {
       $router->setUriSource(Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
    }
    
    // Fetch routes from user
    require(APPPATH . '/config/routes.php');

    // Inject
    return $router;

});



/*
|--------------------------------------------------------------------------
| Dispatcher
|--------------------------------------------------------------------------
|
| It registers the dispatcher as service and will attach event on him to create
| 404 system if the controller/action is not found
| 
*/
$di->set('dispatcher', function() use ($di) {

   $dispatcher = new \Phalcon\Mvc\Dispatcher();

   $evManager = $di->getShared('eventsManager');

   $evManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) use ($di) {


      switch ($exception->getCode()) {

         case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
         case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:

         $dispatcher->forward(
            array(
               'controller' => 'errors',
               'action'     => 'show404',
               )
            );

         return FALSE;

     }
 }
 );

   $dispatcher->setEventsManager($evManager);
   return $dispatcher;

}, TRUE

);
