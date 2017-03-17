<?php
namespace App\WatsonWord;

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
}