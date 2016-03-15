<?php

namespace Nulpunkt\Yesql\Statement;

class Insert
{
    private $sql;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
        $this->mi = new MapInput($modline);
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        $stmt->execute($this->mi->map($args[0]));
        return $db->lastInsertId();
    }
}
