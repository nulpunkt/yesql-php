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
        if (isset($this->statements[$name])) {
            return $this->statements[$name]->execute($this->db, $args);
        } else {
            throw new Exception\MethodMissing($name);
        }
    }

    private function load()
    {
        if ($this->statements) {
            return;
        }

        $this->statements = [];

        $currentMethod = null;
        $collectedSql = "";
        $modline = "";
        foreach (file($this->sqlFile) as $line) {
            if (strpos($line, '--') === 0) {
                $this->saveStatement($currentMethod, $collectedSql, $modline);
                $currentMethod = $this->getMethodName($line);
                $collectedSql = "";
                $modline = $line;
            } else {
                $collectedSql .= $line;
            }
        }
        $this->saveStatement($currentMethod, $collectedSql, $modline);
    }

    private function getMethodName($line)
    {
        preg_match("/\bname:\s*(\S+)/", $line, $m);
        return $m[1];
    }

    private function saveStatement($currentMethod, $collectedSql, $modline)
    {
        if (!$currentMethod) {
            return;
        }

        if (stripos($collectedSql, 'select') === 0) {
            $this->statements[$currentMethod] = new Statement\Select($collectedSql, $modline);
            $currentMethod = null;
        } elseif (stripos($collectedSql, 'insert') === 0) {
            $this->statements[$currentMethod] = new Statement\Insert($collectedSql);
            $currentMethod = null;
        } elseif (stripos($collectedSql, 'update') === 0 || stripos($collectedSql, 'delete') === 0) {
            $this->statements[$currentMethod] = new Statement\Update($collectedSql);
            $currentMethod = null;
        } else {
            throw new Exception\UnknownStatement($collectedSql);
        }
    }
}
