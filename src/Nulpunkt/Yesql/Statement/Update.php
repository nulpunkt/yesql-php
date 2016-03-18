<?php

namespace Nulpunkt\Yesql\Statement;

class Update
{
    private $sql;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        if (isset($args)) {
            $stmt->execute($args);
        } else {
            $stmt->execute();
        }

        return $stmt->rowCount();
    }
}
