<?php
namespace App\WatsonResults;

use App\WatsonWord\WatsonWord;

class WatsonResults
{
  public $words = array();
  public $transcript;
  public $confidence;

  public function parseTimeStamps($timestamps) {
    $index = 0;
    foreach ($timestamps as $timestamp) {
      $word = new WatsonWord($timestamp[0], $timestamp[1], $timestamp[2]);
      $this->addWord($word, $index);
      $index++;
    }
  }

  public function parseFinal($final) {
    $this->confidence = $final['alternatives'][0]['confidence'];
    $this->transcript = $final['alternatives'][0]['transcript'];

    $word_alternatives = $final['word_alternatives'];

    $index = 0;
    foreach ($word_alternatives as $alt) {
      $alts = $alt['alternatives'];
      $st = $alt['start_time'];
      $et = $alt['end_time'];
      foreach ($alts as $word) {
        if ($this->words[$index]->text == $word['word']) {
          $this->words[$index]->setConfidence($word['confidence']);
        } else {
          $altWord = new WatsonWord($word['word'], $st, $et);
          $altWord->setConfidence($word['confidence']);
          $this->words[$index]->addAlternative($altWord);
        }
      }
      $index++;
    }


  }

  public function addWord(WatsonWord $word, $index = -1) {
    if ($index > count($this->words) || $index == -1) {
      $this->words[] = $word;
    } else {
      $this->words[$index] = $word;
    }
  }

  public function setTranscript($transcript) {
    $this->transcript = $transcript;
  }

  public function setConfidence($confidence) {
    $this->confidence = $confidence;
  }
}