<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

require_once(JPATH_COMPONENT.DS.'controller.php');

if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path)) {
		require_once $path;	
	} else {
		$controller = '';	
	}
}

$classname = 'RefAssignController'.$controller;
$controller = new $classname();

$controller->execute(JRequest::getWord('task'));

$controller->redirect();
?>