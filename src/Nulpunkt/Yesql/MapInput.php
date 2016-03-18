<?php

namespace Nulpunkt\Yesql;

class MapInput
{
    private $statement;
    private $modline;

    public function __construct($statement, $modline)
    {
        $this->statement = $statement;
        $this->modline = $modline;
    }

    public function execute($db, $args)
    {
        $inFunc = $this->getInFunc();
        if ($inFunc) {
            $args = call_user_func_array($inFunc, $args);
        }
        return $this->statement->execute($db, $args);
    }

    private function getInFunc()
    {
        preg_match("/\inFunc:\s*(\S+)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : null;
    }
}
