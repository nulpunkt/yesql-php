<?php

namespace Nulpunkt\Yesql\Statement;

class Update implements Statement
{
    private $sql;
    private $stmt;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
    }

    public function execute($db, $args)
    {
        if (!$this->stmt) {
            $this->stmt = $db->prepare($this->sql);
        }

        if (isset($args)) {
            $this->stmt->execute($args);
        } else {
            $this->stmt->execute();
        }

        return $this->stmt->rowCount();
    }
}
