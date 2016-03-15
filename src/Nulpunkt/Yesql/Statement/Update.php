<?php

namespace Nulpunkt\Yesql\Statement;

class Update
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
        if (isset($args[0])) {
            $stmt->execute($this->mapInput($args[0]));
        } else {
            $stmt->execute();
        }

        return $stmt->rowCount();
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
