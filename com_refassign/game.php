<?php
defined('_JEXEC') or die('Can only be loaded from within Joomla');


class Game {
    const GAME_TAG_NAME = "game";

    public $gamenr = "";
    public $date = "";
    public $time = "";
    public $location = "";
    public $teama = "";
    public $teamb = "";
    public $league = "";
    public $refa = "";
    public $refb = "";

    function __construct($gamenr, $date, $time, $location, $teama, $teamb, $league, $refa, $refb) {
        $this->gamenr = $gamenr;
        $this->date = $date;
        $this->time = $time;
        $this->location = $location;
        $this->teama = $teama;
        $this->teamb = $teamb;
        $this->league = $league;
        $this->refa = $refa;
        $this->refb = $refb;
    }

    function toXMLString($indentlvl = 0, $indentstr = "  ") {
        $indent = indentstr($indentstr, $indentlvl);
        $return = "";
        $return .= $indent."<".GAME_TAG_NAME.">\n";

        $return .= $indent.$indentstr."<gameid>".$gamenr."</gameid>\n";
        $return .= $indent.$indentstr."<date>".$date."</date>\n";
        $return .= $indent.$indentstr."<time>".$time."</time>\n";
        $return .= $indent.$indentstr."<location>".$location."</location>\n";
        $return .= $indent.$indentstr."<teama>".$team_a."</teama>\n";
        $return .= $indent.$indentstr."<teamb>".$team_b."</teamb>\n";
        $return .= $indent.$indentstr."<league>".$league."</league>\n";
        $return .= $indent.$indentstr."<refa>".$refa."</refa>\n";
        $return .= $indent.$indentstr."<refb>".$refb."</refb>\n";

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
