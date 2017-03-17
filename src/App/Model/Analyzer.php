<?php
namespace App\Model;

class Analyzer
{
    protected $db = null;

    public function __construct()
    {
        $pdo = new \PDO(
            "mysql:host=mysql;dbname=wordifyme",
            "root",
            "test123"
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $this->db = $pdo;
    }

    public function storeKeyword($keywords, $meetingId)
    {
        $keywords = json_decode($keywords);
        $keywords = $keywords->keywords;

        $row = [];
        $keywordsText = [];
        foreach ($keywords as $keyword) {
            $row[] = "('".$keyword->text."',1)";
            $keywordsText[] = $keyword->text;
        }

        $sql = "INSERT INTO keyword (keyword, dictionaryId) VALUES ".implode(',', $row)." ON DUPLICATE KEY UPDATE counts = 2;";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log(print_r($e->getMessage(), true), 3, '/tmp/err.log');
            return false;
        }

        $sql = "SELECT keywordId FROM keyword WHERE keyword IN ('".implode("','", $keywordsText)."');";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $keywordIds = $stmt->fetchAll(\PDO::FETCH_NUM);
            error_log(print_r($keywordIds, true), 3, '/tmp/err.log');
        } catch (\PDOException $e) {
            error_log(print_r($e->getMessage(), true), 3, '/tmp/err.log');
            return false;
        }

        $row = [];
        foreach ($keywordIds as $keywordId) {
            $row[] = "(".$keywordId[0]. ",". $meetingId .", 1)";
        }

        $sql = "INSERT INTO meeting_keywords (keywordId, meetingId, confidence) VALUES ".implode(',', $row).";";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } catch (\PDOException $e) {
            error_log(print_r($e->getMessage(), true), 3, '/tmp/err.log');
            return false;
        }

        return true;
    }
}
