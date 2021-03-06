<?php
namespace App\Controller;

use App\Model\WatsonResults;

class JSONParserController {

  public $watsonResults;
  public $path = "../data";
  public $servername = "mysql";
  public $dbName = "wordifyme";
  public $username = 'root';
  public $password = 'test123';
  public $currentFolder;

  public function parseJSONFile($request, $args) {
    $id = $request->getAttribute('id');

    $files = scandir($this->path);
    $contents = NULL;

      foreach ($files as $dir) {
          if (preg_match('/(1)+_/', $dir)) {
          $this->currentFolder = $dir;
          $contents = file_get_contents($this->path. '/' . $dir . '/' . $id . '_response_file.json');
      }
    }
      if (!$contents) {
        return false;
    }

      $jsonObjects = $this->splitIntoJSONObjects($contents);
    $this->watsonResults = new WatsonResults();
    $this->watsonResults->meetingId = $id;

    foreach ($jsonObjects as $jsonObject) {
      $timestamps = $jsonObject['results'][0]['alternatives'][0]['timestamps'];
      $this->watsonResults->parseTimeStamps($timestamps);
      if ($jsonObject['results'][0]['final'] == true) {
        $this->watsonResults->parseFinal($jsonObject['results'][0]);
      }
    }

    $this->writeToDB();
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

  public function writeToDB() {
    try {
      $conn = new \PDO("mysql:host=$this->servername;dbname=$this->dbName", $this->username, $this->password);
      $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\Exception $e) {
      echo $e->getMessage();
      return;
    }

    foreach ($this->watsonResults->words as $word) {
      $word->insertWord($this->watsonResults->meetingId, $conn);
    }

    $this->storeTranscript($conn);

    $file = '../data/'.$this->currentFolder . '/' . $this->watsonResults->meetingId . '.analyze';
    file_put_contents($file, '');

  }

  public function storeTranscript($conn) {
      $sql = "INSERT INTO wordifyme.transcript (transcript, confidence) VALUES (:transcript, :confidence) ";

      $query = $conn->prepare($sql);
      try {
          $query->execute(array(':transcript'=> $this->watsonResults->transcript, ':confidence' => 1));
          $id = $conn->lastInsertId();
      } catch (\Exception $e) {
          echo $e->getMessage();
      }

      $sql = "UPDATE meeting SET transcriptId = $id WHERE meetingId = ".$this->watsonResults->meetingId;
      error_log($sql.PHP_EOL, 3, '/tmp/err.log');
      try {
        $query = $conn->prepare($sql);
        $query->execute();

      } catch (\Exception $e) {
          echo $e->getMessage();
      }
  }

}
