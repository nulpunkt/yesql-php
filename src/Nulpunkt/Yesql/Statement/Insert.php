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
        $this->mi = new MapInput($modline);
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        if ($this->oneOrMany() == 'one') {
            $stmt->execute($this->mi->map($args[0]));
            return $db->lastInsertId();
        } else {
            $ids = [];
            foreach ($args[0] as $in) {
                $stmt->execute($this->mi->map($in));
                $ids[] = $db->lastInsertId();
            }
            return $ids;
        }
    }

    private function oneOrMany()
    {
        preg_match("/\boneOrMany:\s*(one|many)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : "one";
    }
}
