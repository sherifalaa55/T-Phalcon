<?php
/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
|
| Register directories we will fetch to find any class you try to call
|
*/
$loader->registerDirs(array(

	$config->application->controllersDir,
	$config->application->modelsDir,
	$config->application->librariesDir

));
