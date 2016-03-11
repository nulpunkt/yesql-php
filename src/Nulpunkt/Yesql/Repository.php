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
        $stmt = $this->db->prepare($this->methods[$name]);
        if (isset($args[0])) {
            $stmt->execute($args[0]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
            } elseif ($currentMethod && stripos($line, 'SELECT') === 0) {
                $this->methods[$currentMethod] = $line;
                $currentMethod = null;
            }
        }

    }
}
