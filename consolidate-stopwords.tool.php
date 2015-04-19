<?php

$file = 'stopwords.de.txt';

$stopwords = file_get_contents($file);

$stopwords = trim($stopwords);
$stopwords = explode("\n", $stopwords);
$stopwords = array_unique($stopwords);
natsort($stopwords);
$stopwords = implode("\n", $stopwords);
file_put_contents($file, $stopwords);

?>