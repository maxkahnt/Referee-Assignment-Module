<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT.DS.'helper.php');

class RefAssignModelRefAssign extends JModel {
	function canSeeTable() {
		$dbo = &JFactory::getDBO();
		$user = &JFactory::getUser();
		
		if($user->id != 0) {		
			$query = 'SELECT lvl FROM #__refassign_usr where id='.$user->id;
			$dbo->setQuery($query);
			return $dbo->loadResult() > 0;
		} else {
			return false;
		}	
	}
	
	function getTable() {
		return get_content();		
	}
}
?>