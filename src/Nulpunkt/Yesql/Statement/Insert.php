<?php

namespace Nulpunkt\Yesql\Statement;

class Insert
{
    private $sql;
    private $modline;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
        $this->modline = $modline;
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        $stmt->execute($args);
        return $db->lastInsertId();
    }

    private function oneOrMany()
    {
        preg_match("/\boneOrMany:\s*(one|many)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : "one";
    }
}
