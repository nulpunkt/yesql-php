<?php

namespace Nulpunkt\Yesql\Statement;

class Update
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
        if (isset($args[0])) {
            $stmt->execute($this->mi->map($args[0]));
        } else {
            $stmt->execute();
        }

        return $stmt->rowCount();
    }
}
