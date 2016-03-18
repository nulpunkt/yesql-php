<?php

namespace Nulpunkt\Yesql;

class Repository
{
    private $db;
    private $sqlFile;
    private $statements = [];
    private $argumentMapper = [];

    public function __construct(\PDO $db, $sqlFile)
    {
        $this->db = $db;
        $this->sqlFile = $sqlFile;
    }

    public function __call($name, $args)
    {
        $this->load();
        if (isset($this->statements[$name])) {
            return $this->statements[$name]->execute(
                $this->db,
                $args
            );
        } else {
            throw new Exception\MethodMissing($name);
        }
    }

    private function load()
    {
        if ($this->statements) {
            return;
        }

        $currentMethod = null;
        $collectedSql = "";
        $modline = "";
        foreach (file($this->sqlFile) as $line) {
            $isComment = strpos($line, '--') === 0;
            if ($isComment && ($nextMethod = $this->getMethodName($line))) {
                if ($currentMethod) {
                    $this->statements[$currentMethod] = new MapInput(
                        $this->createStatement($collectedSql, $modline),
                        $modline
                    );
                }
                $collectedSql = "";
                $currentMethod = $nextMethod;
                $modline = $line;
            } elseif (!$isComment) {
                $collectedSql .= $line;
            }
        }
        $this->statements[$currentMethod] = new MapInput(
            $this->createStatement($collectedSql, $modline),
            $modline
        );
    }

    public function getMethodName($line)
    {
        preg_match("/\bname:\s*(\S+)/", $line, $m);
        return isset($m[1]) ? $m[1] : null;
    }

    private function createStatement($collectedSql, $modline)
    {
        if (stripos($collectedSql, 'select') === 0) {
            return new Statement\Select($collectedSql, $modline);
        } elseif (stripos($collectedSql, 'insert') === 0) {
            return new Statement\Insert($collectedSql, $modline);
        } elseif (stripos($collectedSql, 'update') === 0 || stripos($collectedSql, 'delete') === 0) {
            return new Statement\Update($collectedSql, $modline);
        } else {
            throw new Exception\UnknownStatement($collectedSql);
        }
    }
}
