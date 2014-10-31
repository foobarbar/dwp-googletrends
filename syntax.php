<?php
/**
 * DokuWiki Plugin googletrends (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author   Vincent Tscherter <tscherter@karmin.ch>
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
	    $match = preg_replace("/^.*?>(.*)}}$/", "$1", $match);
		$match = preg_replace("/[^,a-zA-Z0-9 +]/", "", $match);
		//$match = preg_replace("/ /", "+%2B", $match);
		$match = explode(",", $match);
        return $match;
    }

    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml') return false;
      
		$renderer->doc .= '<script type="text/javascript" src="//www.google.ch/trends/embed.js?hl=de&q='
		 .join($data, ',+')
		 .'&cmpt=q&content=1&cid=TIMESERIES_GRAPH_0&export=5&w=500&h=330"></script>';
        return true;
    }
}
