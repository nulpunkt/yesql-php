<?php

namespace Nulpunkt\Yesql;

class Repository
{
    protected $db;
    private $sqlFile;
    private $statements = [];
    private $argumentMapper = [];

    public function __construct(\PDO $db, $sqlFile)
    {
        $this->db = $db;
        $this->sqlFile = $sqlFile;

        $this->statements = (new Statement\Factory)->createStatements($this->sqlFile);
    }

    public function __call($name, $args)
    {
        if (isset($this->statements[$name])) {
            return $this->statements[$name]->execute($this->db, $args);
        } else {
            throw new Exception\MethodMissing($name);
        }
    }
}
