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

  function insertWord($meetingId, $conn) {
    error_log("HERE".PHP_EOL, 3, '/tmp/err.log');
    $sql = "INSERT INTO wordifyme.meeting_words (meetingWordId,meetingId,word,startTimestamp,endTimestamp,confidence) VALUES (:meetingWordId,:meetingId,:word,:startTimestamp,:endTimestamp,:confidence)";
    //$query = "INSERT INTO hackathon.meeting_words (meetingWordId,meetingId,word,startTimestamp,endTimestamp,confidence) VALUES (NULL,1, 'test', NULL, NULL, 1)";
    $query = $conn->prepare($sql);
    try {
      $query->execute(array(':meetingWordId' => NULL, ':meetingId' => $meetingId, ':word' => $this->text, ':startTimestamp' => $this->startTime, ':endTimestamp' => $this->endTime, ':confidence' => $this->confidence));
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }

  function insertAlternative($wordId, WatsonWord $word) {
    $query = "INSERT INTO `wordifyMe`.`word_alternative` (
    `wordAlternativeId`, `meetingWordId`, `confidence`) VALUES (
    NULL, `$wordId`, `$word->confidence`)";
  }
}
