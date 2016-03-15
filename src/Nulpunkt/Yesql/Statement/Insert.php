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
        $in = $this->mapInput($args[0]);
        $stmt = $db->prepare($this->sql);
        $stmt->execute($in);
        return $db->lastInsertId();
    }

    private function mapInput($i)
    {
        $inFunc = $this->getInFunc();
        if ($inFunc && strpos($inFunc, '->') === 0) {
            return call_user_func([$i, substr($inFunc, 2)]);
        }

        return $i;
    }

    private function getInFunc()
    {
        preg_match("/\inFunc:\s*(\S+)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : null;
    }
}
