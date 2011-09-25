<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

jimport('joomla.application.component.controller');

class AssignmentStatus {
  const Undefined = -1;
  const NotAvailable = 0;
  const Acceptable = 1;
  const Preferred = 2;
  const Assigned = 1000;
}

class ErrorMsg {
  const BadAssign = "Ungültige Zuordnung. Das sollte nicht passieren. Bitte wende dich an den Administrator! Der Eintrag wurde zurückgesetzt.";
  const FixedAssign = "Kann Zuordnung nicht ändern, du bist bereits für dieses Spiel vorgesehen. Bitte wende dich an den Ansetzer deines Vereins.";
  const BadUser = "Du hast keinen Zugriff. Bitte melde dich an. Solltest du bereits angemeldet sein, wende dich an den Administrator!";
}

class RefAssignController extends JController {
	function display() {
		parent::display();	
	}
	
// xml response
// empty error will cause status to be returned "OK", else it will contain the error message
function createXMLforGame($gameid, $error, $values) {
  header("Content-Type: text/xml; charset=utf-8");    
  $return = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  $return .= "<game id=\"".$gameid."\">";
    $return .= "<status>";
      $return .= ($error == null || $error == "") ? "OK" : $error; //CHECK maybe null == "" ?!
    $return .= "</status>";
  $return .= $values;
  $return .= "</game>";
  echo $return;
}

function listgames() {
		$gameid = JRequest::getVar("gameid");
		$model = &$this->getModel();
		if($model->canSeeTable()) {
			$dbo = &JFactory::getDBO();
			$query = "SELECT id, pref_val FROM `#__refassign_rel` WHERE game_id=".$gameid;
			$dbo->setQuery($query);
			$results = $dbo->loadAssocList();
			$refs = "";
			foreach($results as $entry) {
				$id = $entry['id'];
				$lvl = $entry['pref_val'];
				$usr = JUser::getInstance($id)->name;
				$isYou = "";
				if ($id == JFactory::getUser()->id) {
					$isYou = "you=\"true\" ";
				}
				$refs .= "<ref name=\"$usr\" assignmentstatus=\"$lvl\" ".$isYou."/>\n";
			}
			
			$this->createXMLforGame($gameid, "", $refs);
			return true;
		} else {
			$this->createXMLforGame($gameid, ErrorMsg::BadUser, "");
			return false;		
		}
	}
	
	function cycleownstatus() {
		$gameid = JRequest::getVar("gameid");
		$id = JFactory::getUser()->id;

		$dbo = &JFactory::getDBO();
		$query = "SELECT pref_val FROM `#__refassign_rel` WHERE game_id=".$gameid." AND id=".$id;
		$dbo->setQuery($query);
		$current_gamestatus = $dbo->loadResult();
		
		// TODO assign Undefined if so

		switch($current_gamestatus) {
		case AssignmentStatus::Undefined:
		  $desired_gamestatus = AssignmentStatus::NotAvailable;
		  break;
		case AssignmentStatus::NotAvailable:
		  $desired_gamestatus = AssignmentStatus::Acceptable;
		  break;
		case AssignmentStatus::Acceptable:
		  $desired_gamestatus = AssignmentStatus::Preferred;
		  break;
		case AssignmentStatus::Preferred:
		  $desired_gamestatus = AssignmentStatus::NotAvailable;
		  break;
		case AssignmentStatus::Assigned:
		  $this->createXMLforGame(gameid, ErrorMsg::FixedAssign, "");
		  return false;
		  break;
		default:
		  $this->createXMLforGame(gameid, ErrorMsg::BadAssign, "");
		  $dbo->execute("UPDATE `#__refassign_rel` SET pref_val=".AssignmentStatus::Undefined." WHERE game_id=".$gameid." AND id=".$id);
		  return false; // TODO check consequences of returning false here
		  break;
		}

		$query = "UPDATE `#__refassign_rel` SET pref_val=".$desired_gamestatus." WHERE game_id=".$gameid." AND id=".$id;
		$dbo->execute($query);

		$this->createXMLforGame($gameid, "", "");
		return true;
	}
}
?>