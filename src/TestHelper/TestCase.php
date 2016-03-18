<?php

namespace TestHelper;

abstract class TestCase extends \PHPUnit_Extensions_Database_TestCase
{
    private $db;

    public function __construct()
    {
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        );

        $this->db = new \PDO(\MYSQL_SERVER_DSN, \DB_USER, \DB_PASS, $options);

        $fixture = file_get_contents(__DIR__.'/mysql-fixture.sql');
        $this->db->exec('USE yesql');
        $stmt = $this->db->prepare($fixture);
        $stmt->execute();
    }

    public function createQueryDataset($tables)
    {
        $ds = new \PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        foreach ($tables as $table => $query) {
            $ds->addTable($table, $query);
        }
        return $ds;
    }

    public function getDatabase()
    {
        return $this->db;
    }

    public function getConnection()
    {
        return $this->createDefaultDBConnection($this->db, 'yesql');
    }
}
