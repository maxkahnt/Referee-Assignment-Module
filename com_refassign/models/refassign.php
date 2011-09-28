<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

jimport('joomla.application.component.model');

require_once(JPATH_COMPONENT.DS.'helper.php');
require_once(JPATH_COMPONENT.DS.'game.php');

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


    function getBBVData() {
        $data = get_content();
        if (count($data = array()) == 0) {
            return "ERROR";
        } // else
        foreach ($data as $game) {
            $gamenr = $game['Spielnummer'];
            $date = $game['Datum'];
            $time = $game['Zeit'];
            $location = $game['Halle'];
            $league = $game['Liga'];
            $teama = $game['Team A'];
            $teamb = $game['Team B'];
            $refa = $game['SR 1'];
            $refb = $game['SR 2'];
            $newGame = new Game($gamenr, $date, $time, $location, $teama, $teamb, $league, $refa, $refb);
            // append to return array
        }

        // return return array
        
    }



    function updateGameData() {
        // get data from bbv
        $gameData = getBBVData();

        // iterate over games
            // retrieve game in database (by id)
            // add new games
            // old games with same values are okay
            // old games with new values cause trouble!

    }



}



?>