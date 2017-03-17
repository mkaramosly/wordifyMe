<?php
namespace App\Model;

class WatsonWord
{
  public $text;
  public $startTime;
  public $endTime;
  public $confidence;
  public $alternatives;

  function __construct($t, $st, $et)
  {
    $this->text = $t;
    $this->startTime = $st;
    $this->endTime = $et;
  }

  function addAlternative(WatsonWord $word) {
    $this->alternatives = $word;
  }

  function setConfidence($confidence) {
    $this->confidence = $confidence;
  }

  function insertWord( $meetingId) {
    $query = "INSERT INTO `wordifyMe`.`meeting_words` (`meeting_word_id`,`meetingId`, `word`,
      `startTimestamp`, `endTimestamp`, `confidence`) VALUES (
      NULL, `$meetingId`, `$this->text`, `$this->startTime`, `$this->endTime`,`$this->confidence`
      )";

    foreach ($this->alternatives as $alternative) {
      $alternative->insertWord($meetingId);
    }
  }

  function insertAlternative($wordId, WatsonWord $word) {
    $query = "INSERT INTO `wordifyMe`.`word_alternative` (
    `wordAlternativeId`, `meetingWordId`, `confidence`) VALUES (
    NULL, `$wordId`, `$word->confidence`)";
  }
}