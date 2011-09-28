<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');

class XMLGameView {
    $games = array();

    function __construct($games) {
        $this->games = $games;
    }

    function toString($fullxml = false) {
        $return = "";
        if($fullxml) {
            $return .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"; 
        }
        $return .= "<games>\n";
        if(is_array($games)) {
            foreach($games as $game) {
                if($game instanceof Game) { //TODO: add/fix import
                    $return .= $game->toXMLString(1);
                }
            }
        } else if($games instanceof Game) {
            $return .= $game->toXMLString(1);
        }
        $return .= "</games>\n";

        return $return;
    }

    function show() {
        header("Content-Type: text/xml; charset=utf-8");
        echo toString(true);
    }
}

?>
