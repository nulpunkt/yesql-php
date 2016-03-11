<?php

namespace Nulpunkt\Yesql;

class Repository
{
    private $db;
    private $sqlFile;
    private $statements;

    public function __construct(\PDO $db, $sqlFile)
    {
        $this->db = $db;
        $this->sqlFile = $sqlFile;
    }

    public function __call($name, $args)
    {
        $this->load();
        return $this->statements[$name]->execute($this->db, $args);
    }

    private function load()
    {
        if ($this->statements) {
            return;
        }

        $this->statements = [];

        $currentMethod = null;
        $collectedSql = "";
        foreach (file($this->sqlFile) as $line) {
            if (strpos($line, '--') === 0) {
                $this->saveStatement($currentMethod, $collectedSql);
                preg_match("/\bname:\s*([a-zA-Z_0-9]+)/", $line, $m);
                $currentMethod = $m[1];
                $collectedSql = "";
            } else {
                $collectedSql .= $line;
            }
        }
        $this->saveStatement($currentMethod, $collectedSql);
    }

    private function saveStatement($currentMethod, $collectedSql)
    {
        if (!$currentMethod) {
            return;
        }

        if (stripos($collectedSql, 'select') === 0) {
            $this->statements[$currentMethod] = new Statement\Select($collectedSql);
            $currentMethod = null;
        } elseif (stripos($collectedSql, 'insert') === 0) {
            $this->statements[$currentMethod] = new Statement\Insert($collectedSql);
            $currentMethod = null;
        } else {
            $this->statements[$currentMethod] = new Statement\Update($collectedSql);
            $currentMethod = null;
        }
    }
}
