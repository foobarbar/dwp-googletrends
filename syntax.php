<?php
/**
 * DokuWiki Plugin Google Trends (Syntax Component)
 *
 * @license  GPL3
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


    public function handle($match, $state, $pos, Doku_Handler $handler){
		$match = explode("|", preg_replace("/^.*?>(.*)}}$/", "$1", $match));
		// terms
		$terms = preg_replace("/[^,a-zA-Z0-9 +]/", "", $match[0]);
		$data["terms"] = explode(",", $terms );
		// options
		$data["hl"] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$data["w"] = 500;
		$data["h"] = 500;
		if (isset($match[1])) {
		  if (preg_match("/hl=([a-z]{2})/", $match[1], $matches)) $data['hl'] = $matches[1];
		  if (preg_match("/w=([0-9]+)/", $match[1], $matches)) $data['w'] = $matches[1];
		  if (preg_match("/h=([0-9]+)/", $match[1], $matches)) $data['h'] = $matches[1]; 
		}
        return $data;
    }

    public function render($mode, Doku_Renderer $renderer, $data) {
        if($mode != 'xhtml') return false;
            $renderer->doc .= '<script type="text/javascript" src="//www.google.com/trends/embed.js?'
				.'hl='.$data["hl"]
				.'&w='.$data["w"]
				.'&h='.$data["h"]
				.'&q='.join($data["terms"], ',+')
             .'&cmpt=q&content=1&cid=TIMESERIES_GRAPH_0&export=5"></script>';
        return true;
    }
}
