<?php
/*
|--------------------------------------------------------------------------
| Volt template engine
|--------------------------------------------------------------------------
|
| Here you can list below all the things you want to add on Volt
|
| Examples :
|
| Add a function :
|
| $compiler->addFunction('shuffle', 'str_shuffle');
|
| 	-> Will make a 'shuffle' volt function with the PHP str_shuffle one
| 
| Add a filter :
|
| $compiler->addFilter('capitalize', 'lcfirst');
| 
|--------------------------------------------------------------------------
| For more details about the volt system : 
| @link http://docs.phalconphp.com/en/latest/reference/volt.html
| 
*/

// Here we get the compiler before adding anything
$compiler = $volt->getCompiler();

/**
 * Right below you can add whatever you want about the volt template engine
 */