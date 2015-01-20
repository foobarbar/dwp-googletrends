<?php
/**
 * DokuWiki Plugin Google Trends (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author   Vincent Tscherter <tscherter@karmin.ch>
 * 
 * This plugin is based on code from the googledrawing pluing by Linus Brimstedt & Michael Stewart
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_googletrends extends DokuWiki_Syntax_Plugin {
    public function getType() {
        return 'substition';
    }

    public function getPType() {
        return 'normal';
    }

    public function getSort() {
        // Must be before external link (330)
        return 305;
    }


    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{googletrends>.*?}}',$mode,'plugin_googletrends');
    }


    public function handle($match, $state, $pos, &$handler){
        $glob_match = preg_replace("/^.*?>(.*)}}$/", "$1", $match);
        if(preg_match("/(.*)\|(.*)/", $glob_match, $matches)) {
            $req_match = preg_replace("/[^,a-zA-Z0-9 +]/", "", $matches[1]);
            $opt_match = $matches[2];
        } else {
            $req_match = preg_replace("/[^,a-zA-Z0-9 +]/", "", $glob_match);
            $opt_match = 'fr';
        }
        $req_match = explode(",", $req_match);

        $all_match['data'] = $req_match;
        $all_match['hl'] = $opt_match;
        return $all_match;
    }

    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml') return false;
            $renderer->doc .= '<script type="text/javascript" src="//www.google.com/trends/embed.js?hl='.$data["hl"].'&q='
             .join($data["data"], ',+')
             .'&cmpt=q&content=1&cid=TIMESERIES_GRAPH_0&export=5&w=500&h=500"></script>';
        return true;
    }
}
