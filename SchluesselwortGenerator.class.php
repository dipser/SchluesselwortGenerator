<?php

/**
 * Generiert Schlüsselwörter anhand einer HTML-Seite.
 * 
 * @author Aurelian Hermand, aurelian@hermand.de
 */


class SchlusselwortGenerator {
  
	/** URL der HTML-Webseite. */
	private $url = '';
	
	/** Original HTML. */
	private $orig_html = '';
	
	/** Bearbeitetes HTML. */
	private $html = '';
	
	/** Array von Stoppwörtern. */
	private $stopwords = array();
	
	/** Priorisierte Schlüsselwort-Liste. */
	private $rankedKeywords = array();
	
	/**
	* Konstruktor.
	*/
	function SchlusselwortGenerator($url) {
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
	
	private function removeTagsWithContent($tagArray) {
		foreach ($tagArray as $tag) {
			$this->html = preg_replace('#<'.$tag.'(.*?)>(.*?)</'.$tag.'>#is', '', $this->html);
		}
	}
	
	private function removeTagsWithDuplication() {
		$special = array(
			// TagText-TagAttribut => Duplikate
			'img-alt' => 3,
			'img-title' => 4,
			'a-title' => 10,
			'a' => 10,
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
		foreach ($special as $tag => $dups) {
			if (strpos($tag, '-') !== false) {
				$split = explode('-', $tag);
				$tag = $split[0];
				$attr = $split[1];
				$result = preg_replace('/<img[^>]*alt="([^"]*)"[^>]*>/', " $1 ", $result);
			} else {
				
			}
		}
	}
	
	private function makeRankedKeywords() {
		
	}
	
	private function removeStopwords() {
		
	}
	
	function keywords() {
		
		// URL einlesen
		$this->fetch();
		
		// HTML Bereinigungen
		$this->removeTagsWithContent(array('head', 'script', 'nav', 'footer'));
		$this->removeTagsWithDuplication();
		
		// Umwandlung in eine priorisierte Schlüsselwort-Liste
		$this->makeRankedKeywords();
		
		// Keywordliste bereinigen
		$this->removeStopwords();
		
		
		return $this->rankedKeywords;
	}
}



// Beispiel
if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ) {
	
	$stopwords = array('aber', 'als', 'am', 'an', 'auch', 'auf', 'aus', 'bei', 'bin', 'bis', 'bist', 'da', 'dadurch', 'daher', 'darum', 'das', 'daß', 'dass', 'dein', 'deine', 'dem', 'den', 'der', 'des', 'dessen', 'deshalb', 'die', 'dies', 'dieser', 'dieses', 'doch', 'dort', 'du', 'durch', 'ein', 'eine', 'einem', 'einen', 'einer', 'eines', 'er', 'es', 'euer', 'eure', 'für', 'hatte', 'hatten', 'hattest', 'hattet', 'hier', 'hinter', 'ich', 'ihr', 'ihre', 'im', 'in', 'ist', 'ja', 'jede', 'jedem', 'jeden', 'jeder', 'jedes', 'jener', 'jenes', 'jetzt', 'kann', 'kannst', 'können', 'könnt', 'machen', 'mein', 'meine', 'mit', 'muß', 'mußt', 'musst', 'müssen', 'müßt', 'nach', 'nachdem', 'nein', 'nicht', 'nun', 'oder', 'seid', 'sein', 'seine', 'sich', 'sie', 'sind', 'soll', 'sollen', 'sollst', 'sollt', 'sonst', 'soweit', 'sowie', 'und', 'unser', 'unsere', 'unter', 'vom', 'von', 'vor', 'wann', 'warum', 'was', 'weiter', 'weitere', 'wenn', 'wer', 'werde', 'werden', 'werdet', 'weshalb', 'wie', 'wieder', 'wieso', 'wir', 'wird', 'wirst', 'wo', 'woher', 'wohin', 'zu', 'zum', 'zur', 'über');
	$stopwords = array_merge($stopwords, array('seinem', 'lassen', 'sondern', 'hat', 'ihnen', 'keine', 'ihren', 'trotz', 'uns', 'wollen'));

	
	$sg = new SchluesselwortGenerator('webseite.html');
	$sg->setStopwords($stopwords);
	//$sg->setStopwords('stopwords.de.txt');
	echo $sg->keywords();
	
}

?>
