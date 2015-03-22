<?php

// DB-Einträge der Cronjobs
$data = [
  '*',
  '*/4'
];


// Finde Einträge...
// Ist es möglich mithilfe von $selects automatisch $data zu durchsuchen und die passenden Einträge zu finden?
// Alle möglichen Repräsentationen müssten getestet werden. => Repräsentationen mit PHP generieren und abfragen.
$selects = [
  '11.11.2015',
  '*',
  '*/2'
];


class CronSelect
{
  private $cron = '';
  function CronSelect() {}
  function setByCron($cron) {}
  function setByTime($time) {}
  function setByDate($date) { $this->setByTime( strtotime($date) ); }
  function getRegexpRepresentation() {}
}
$cs = new CronSelect;
$cs->setByDate('11.11.2015');





// => SELECT * FROM tab WHERE cron REGEXP $cs->getRegexpRepresentation()

?>
