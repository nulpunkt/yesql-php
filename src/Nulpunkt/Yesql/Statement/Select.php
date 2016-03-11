<?php

namespace Nulpunkt\Yesql\Statement;

class Select
{
    private $sql;
    private $oneOrMany;

    public function __construct($sql, $oneOrMany)
    {
        $this->sql = $sql;
        $this->oneOrMany = $oneOrMany;
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        if (isset($args[0])) {
            $stmt->execute($args[0]);
        } else {
            $stmt->execute();
        }
        if ($this->oneOrMany == 'one') {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
}
