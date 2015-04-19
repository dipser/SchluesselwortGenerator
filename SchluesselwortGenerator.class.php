<?php

/**
* Generiert Schlüsselwörter anhand einer HTML-Seite.
* 
* @author Aurelian Hermand, aurelian@hermand.de
* @version 1.0.0 - 19.04.2015 - ah - Initiales Skript
*/

//ini_set("display_errors", "1");
//error_reporting(E_ALL);


class SchluesselwortGenerator {
  
	/** URL der HTML-Webseite. */
	private $url = '';
	
	/** Original HTML. */
	private $orig_html = '';
	
	/** Bearbeitetes HTML. */
	private $html = '';
	
	/** Array von Stoppwörtern. */
	private $stopwords = array();
	
	/** Priorisierte Schlüsselwort-Liste. */
	private $ranked_keywords = array();
	
	/**
	* Konstruktor.
	*/
	function SchluesselwortGenerator($url) {
		$this->url = $url;
	}
  
	/**
	* Runterladen der HTML-Webseite.
	*/
	private function fetch() {
		$this->html = $this->orig_html = file_get_contents( $this->url );
	}
	
	/**
	* Stoppwörter setzen.
	* @param mixed Array oder Dateipfad.
	*/
	function setStopwords($stopwords) {
		if ( is_string($stopwords) ) { // URL
			$this->stopwords = explode("\n", trim(file_get_contents($stopwords)));
		}
		elseif ( is_array($stopwords) ) { // Array
			$this->stopwords = $stopwords;
		}
	}
	
	/**
	* Tags entfernen.
	* @param String Tagname
	*/
	private function removeTagsCompletely($tagArray) {
		foreach ($tagArray as $tag) {
			$this->html = preg_replace('#<'.$tag.'(>|\s.*?>)(.*?)</'.$tag.'>#is', ' ', $this->html);
		}
	}
	
	/**
	* Bestimmte Taginhalt multiplizieren und Tags entfernen.
	*/
	private function removeTagsWithMultiplication() {
		$special = array(
			// TagText-TagAttribut => Multiplikate
			'img-alt' => 3,
			'img-title' => 4,
			'a-title' => 5,
			'a' => 5,
			'h1' => 20,
			'h2' => 9,
			'h3' => 8,
			'h4' => 7,
			'h5' => 6,
			'h6' => 5,
			'b' => 4,
			'u' => 3,
			'i' => 2,
			'em' => 3,
			'strong' => 4,
			'cite' => 2,
			'blockquote' => 2
		);
		foreach ($special as $tag => $multis) {
			if (strpos($tag, '-') !== false) {
				$split = explode('-', $tag);
				$tag = $split[0];
				$attr = $split[1];
				$this->html = preg_replace('#<'.$tag.'[^>]*'.$attr.'="([^"]*)"[^>]*>#i', str_repeat(' $1 ', $multis), $this->html);
			} else {
				$this->html = preg_replace('#<'.$tag.'(>|\s.*?>)(.*?)</'.$tag.'>#is', str_repeat(' $2 ', $multis), $this->html);
			}
		}
		$this->html = strip_tags($this->html);
	}

	/**
	* HTML-Entitäten und bestimmte Zeichen entfernen.
	*/
	private function removeSigns() {
		$this->html = preg_replace('/&#?[a-z0-9]{2,8};/i', ' ', $this->html);
		$this->html = str_replace(array('.', '&', ':', ',', '„', '“', '»', '«', ' - ', ' – '), ' ', $this->html);
	}

	/**
	* Stoppwörter entfernen.
	*/
	private function removeStopwords() {
		$this->html = preg_replace('/\b('.implode('|', $this->stopwords).')\b/i', '', $this->html);
	}
	
	/**
	* Array mit Anzahl erstellen.
	*/
	private function makeRankedKeywordList() {
		$this->html = preg_replace('/\s+/', ' ', $this->html);
		$this->html = trim($this->html);
		$keywords = explode(' ', $this->html);
		foreach ($keywords as $k) {
			if ( !isset($this->ranked_keywords[$k]) ) { $this->ranked_keywords[$k] = 1; }
			$this->ranked_keywords[$k] += 1;
		}
	}
	
	/**
	* Schlüsselwörter erstellen.
	*/
	function keywords() {
		
		// URL einlesen
		$this->fetch();
		
		// HTML Bereinigungen
		$this->removeTagsCompletely(array('head', 'script', 'nav', 'footer'));
		$this->removeTagsWithMultiplication();
		$this->removeSigns();
		
		// Text bereinigen
		$this->removeStopwords();

		// Umwandlung in eine priorisierte Schlüsselwort-Liste
		$this->makeRankedKeywordList();
		
		
		return $this->ranked_keywords;
	}
}



// Beispiel
if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ) {

	echo 'Beispiel...<br />';
	
	$sg = new SchluesselwortGenerator('webseite.txt');
	$sg->setStopwords('stopwords.de.txt');
	print_r( $sg->keywords() );
	
}

?>