<?php

namespace Nulpunkt\Yesql\Statement;

class Insert implements Statement
{
    private $sql;
    private $modline;
    private $stmt;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
        $this->modline = $modline;
    }

    public function execute($db, $args)
    {
        if (!$this->stmt) {
            $this->stmt = $db->prepare($this->sql);
        }

        $this->stmt->execute($args);
        return $db->lastInsertId();
    }

    private function oneOrMany()
    {
        preg_match("/\boneOrMany:\s*(one|many)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : "one";
    }
}
