<?php
/**
 * Phalcon StarterKit
 *
 * A simple StarterKit for PhalconPHP. 
 *
 * @package		StarterKit
 * @author		Jeremie Ges & Laurent Schaffner
 * @link		https://github.com/GesJeremie/PhalconPHP-StarterKit
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------

/**
 * ControllerBase
 *
 * Some application features like access control lists, translation, cache, 
 * and template engines are often common to many controllers. In cases like 
 * these the creation of a “base controller” is encouraged to ensure your 
 * code stays DRY. 
 *
 * A base controller is simply a class that extends the Phalcon\Mvc\Controller 
 * and encapsulates the common functionality that all controllers must have. 
 * In turn, your controllers extend the “base controller” and have access 
 * to the common functionality.
 *
 * @package		PhalconPHP
 * @subpackage	Controllers
 * @category	Controllers
 * @link		https://phalcon-php-framework-documentation.readthedocs.org/en/latest/reference/controllers.html
 */

// ------------------------------------------------------------------------

use Phalcon\Mvc\Controller;

// ------------------------------------------------------------------------

class ControllerBase extends Controller
{
	/**
	 * Initialize
	 * 
	 * Phalcon\Mvc\Controller offers the initialize method, which is executed first, 
	 * before any action is executed on a controller. 
	 * 
	 * Note : The use of the "__construct" method is not recommended.
	 * 
	 * @link https://phalcon-php-framework-documentation.readthedocs.org/en/latest/reference/controllers.html#initializing-controllers
	 */
	public function initialize()
	{
	
	}

}
