<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');


class Game {
    const GAME_TAG_NAME = "game";

    public $gamenr = "";
    public $datetime = "";
    public $team_a = "";
    public $team_b = "";
    public $league = "";
    public $ref1 = "";
    public $ref2 = "";

    function __construct($gamenr, $datetime, $team_a, $team_b, $league, $ref1, $ref2) {
        $this->gamenr = $gamenr;
        $this->datetime = $datetime;
        $this->team_a = $team_a;
        $this->team_b = $team_b;
        $this->league = $league;
        $this->ref1 = $ref1;
        $this->ref2 = $ref2;
    }

    function toXMLString($indentlvl = 0, $indentstr = "  ") {
        $indent = indentstr($indentstr, $indentlvl);
        $return = "";
        $return .= $indent."<".GAME_TAG_NAME.">\n";

        $return .= $indent.$indentstr."<number>".$gamenr."</number>\n";
        $return .= $indent.$indentstr."<datetime>".$datetime."</datetime>\n";
        $return .= $indent.$indentstr."<teama>".$team_a."</teama>\n";
        $return .= $indent.$indentstr."<teamb>".$team_b."</teamb>\n";
        $return .= $indent.$indentstr."<league>".$league."</league>\n";
        $return .= $indent.$indentstr."<refone>".$ref1."</refone>\n";
        $return .= $indent.$indentstr."<reftwo>".$ref2."</reftwo>\n";

        $return .= $indent."</".GAME_TAG_NAME.">\n";
    }

    private function indentstr($str, $times) {
        $return = "";
        for($i = 0; $i < $times; ++$i) {
            $return .= $str;
        }
        return $return;
    }
}

?>
