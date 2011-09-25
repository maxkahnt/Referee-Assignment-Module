<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

jimport('joomla.application.component.controller');

class RefAssignController extends JController {
	function display() {
		parent::display();	
	}
	
	function listgames() {
		$model = &$this->getModel();
		header("Content-Type: text/xml; charset=utf-8");    
		if($model->canSeeTable()) {
			$return = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$return .= "<game><status>OK</status>";
			
			$dbo = &JFactory::getDBO();
			$query = "SELECT id, pref_val FROM `#__refassign_rel` WHERE game_id=".JRequest::getVar("gameid");
			$dbo->setQuery($query);
			$results = $dbo->loadAssocList();
			
			foreach($results as $entry) {
				$id = $entry['id'];
				$lvl = $entry['pref_val'];
				$usr = JUser::getInstance($id)->name;
				$isYou = "";
				if ($id == JFactory::getUser()->id) {
					$isYou = "you=\"true\" ";
				}
				$return .= "<ref name=\"$usr\" assignmentstatus=\"$lvl\" ".$isYou."/>\n";
			}
			
			$return .= "</game>";
			echo $return;
			return true;
		} else {
			echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<game><status>ERROR</status></game>";
			return false;		
		}
	}
	
	function cycleownstatus() {
		$gameid = JRequest::getVar("gameid");
		$id = JFactory::getUser()->id;

		header("Content-Type: text/xml; charset=utf-8");    
		$return = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$return .= "<game id=\"".$gameid."\"><status>OK2</status>";
		
		$dbo = &JFactory::getDBO();
		$query = "SELECT pref_val FROM `#__refassign_rel` WHERE game_id=".$gameid." AND id=".$id;
		$dbo->setQuery($query);
		$current_gamestatus = $dbo->loadResult();
		if ($current_gamestatus == 2) {
		  $desired_gamestatus = 4;
		} else if ($current_gamestatus == 4) {
		  $desired_gamestatus = 0;
		} else if ($current_gamestatus == 0) {
		  $desired_gamestatus = 2;
		}
		$query = "UPDATE `#__refassign_rel` SET pref_val=".$desired_gamestatus." WHERE game_id=".$gameid." AND id=".$id;
		$dbo->execute($query);

		$return .= "</game>";
		echo $return;
		return true;
	}
}
?>