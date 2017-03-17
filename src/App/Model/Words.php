<?php
namespace App\Model;

class Words
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

    public function getWords($meetingId)
    {
        $sql = "SELECT meetingWordId, word, startTimestamp FROM meeting_words WHERE meetingId = $meetingId ORDER BY startTimestamp;";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log(print_r($e->getMessage(), true), 3, '/tmp/err.log');
            return false;
        }

        return true;
    }
}
