<?php

namespace TestHelper;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    private $db;

    public function __construct()
    {
        parent::__construct();

        $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];

        $this->db = new \PDO(\MYSQL_SERVER_DSN, \DB_USER, \DB_PASS, $options);
        $this->db->exec('USE yesql');
    }

    abstract protected function getDataSet(): array;

    public function setup(): void
    {
        parent::setup();
        foreach ($this->getDataSet() as $table => $rows) {
            $this->db->prepare("truncate {$table}")->execute();
            $sql = "INSERT " . $this->createIntoSql($table, $rows[0]);
            $stmt = $this->db->prepare($sql);
            foreach ($rows as $row) {
                $stmt->execute($row);
            }
        }
    }

    private function createIntoSql($table, $binds)
    {
        $names = array_keys($binds);
        $namesEscaped = array_map(fn($name) => "`$name`", $names);
        $values = array_map(fn($name) => ":$name", $names);

        return " INTO {$table} (" . implode(", ", $namesEscaped) . ")
                VALUES (" . implode(", ", $values) . ")";
    }

    public function getDatabase()
    {
        return $this->db;
    }

    public function getConnection()
    {
        return $this->createDefaultDBConnection($this->db, 'yesql');
    }

    protected function assertQueryEquals($expected, $sql)
    {
        $this->assertEquals($expected, $this->db->query($sql, \PDO::FETCH_ASSOC)->fetchAll());
    }
}
