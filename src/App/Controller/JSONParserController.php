<?php
namespace App\JSONParserController;

use App\WatsonResults\WatsonResults;

class JSONParserController {

  public $watsonResults;

  public function parseJSONFile() {

    $contents = file_get_contents("../examples/sample_data.txt");
    $jsonObjects = $this->splitIntoJSONObjects($contents);

    $this->watsonResults = new WatsonResults();

    foreach ($jsonObjects as $jsonObject) {
      $timestamps = $jsonObject['results'][0]['alternatives'][0]['timestamps'];
      $this->watsonResults->parseTimeStamps($timestamps);
      if ($jsonObject['results'][0]['final'] == true) {
        $this->watsonResults->parseFinal($jsonObject['results'][0]);
      }
    }
  }

  public function splitIntoJSONObjects($fileContents) {
    $offset = 0;
    $positions = array();
    $positions[] = 0;

    while (($res = strpos($fileContents, "}{", $offset)) !== FALSE) {
      $positions[] = $res;
      $res++;
      $positions[] = $res;
      $offset = $res;
    }
    $positions[] = strlen($fileContents);

    $objects_as_string = array();

    for ($i = 0; $i < count($positions); $i+=2) {
      $objects_as_string[] = substr($fileContents, $positions[$i], $positions[$i+1] - $positions[$i] + 1);
    }

    $jsonObjects = array();
    foreach ($objects_as_string as $string) {
      $jsonObjects[] = json_decode($string, true);
    }

    return $jsonObjects;
  }

}