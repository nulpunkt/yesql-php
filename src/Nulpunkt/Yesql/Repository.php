<?php

namespace Nulpunkt\Yesql;

class Repository
{
    private $db;
    private $sqlFile;
    private $methods;

    public function __construct(\PDO $db, $sqlFile)
    {
        $this->db = $db;
        $this->sqlFile = $sqlFile;
    }

    public function __call($name, $args)
    {
        $this->load();
        return $this->methods[$name]->execute($this->db, $args);
    }

    private function load()
    {
        if ($this->methods) {
            return;
        }

        $this->methods = [];

        $currentMethod = null;
        foreach (file($this->sqlFile) as $line) {
            $line = trim($line);
            if (strpos($line, '--') === 0) {
                preg_match("/\bname:\s*([a-zA-Z_0-9]+)/", $line, $m);
                $currentMethod = $m[1];
            } elseif ($currentMethod && stripos($line, 'select') === 0) {
                $this->methods[$currentMethod] = new Statement\Select($line);
                $currentMethod = null;
            } elseif ($currentMethod && stripos($line, 'insert') === 0) {
                $this->methods[$currentMethod] = new Statement\Insert($line);
                $currentMethod = null;
            }
        }

    }
}
