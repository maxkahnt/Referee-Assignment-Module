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

    function toXMLString($identlvl = 0, $identstr = "  ") {
        $ident = identstr($identstr, $identlvl);
        $return = "";
        $return .= $ident."<".GAME_TAG_NAME.">\n";

        $return .= $ident.$identstr."<number>".$gamenr."</number>\n";
        $return .= $ident.$identstr."<datetime>".$datetime."</datetime>\n";
        $return .= $ident.$identstr."<teama>".$team_a."</teama>\n";
        $return .= $ident.$identstr."<teamb>".$team_b."</teamb>\n";
        $return .= $ident.$identstr."<league>".$league."</league>\n";
        $return .= $ident.$identstr."<refone>".$ref1."</refone>\n";
        $return .= $ident.$identstr."<reftwo>".$ref2."</reftwo>\n";

        $return .= $ident."</".GAME_TAG_NAME.">\n";
    }

    private function identstr($str, $times) {
        $return = "";
        for($i = 0; $i < $times; ++$i) {
            $return .= $str;
        }
        return $return;
    }
}

?>
