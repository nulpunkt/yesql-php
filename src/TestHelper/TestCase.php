<?php

namespace TestHelper;

use PHPUnit\DbUnit\TestCaseTrait;

abstract class TestCase extends UnitTestCase
{
    use TestCaseTrait;

    private $db;

    public function __construct()
    {
        parent::__construct();
        $options = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];

        $this->db = new \PDO(\MYSQL_SERVER_DSN, \DB_USER, \DB_PASS, $options);

        $fixture = file_get_contents(__DIR__.'/mysql-fixture.sql');
        $this->db->exec('USE yesql');
        $stmt = $this->db->prepare($fixture);
        $stmt->execute();
    }

    public function createQueryDataset($tables)
    {
        $ds = new \PHPUnit\DbUnit\DataSet\QueryDataSet($this->getConnection());
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
