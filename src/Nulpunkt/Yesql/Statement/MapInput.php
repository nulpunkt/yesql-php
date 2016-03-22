<?php

namespace Nulpunkt\Yesql\Statement;

class MapInput implements Statement
{
    private $statement;
    private $modline;

    public function __construct($statement, $modline)
    {
        $this->statement = $statement;
        $this->modline = $modline;
        $this->inFunc = $this->getInFunc();
    }

    public function execute($db, $args)
    {
        if ($this->inFunc) {
            $args = call_user_func_array($this->inFunc, $args);
        }
        return $this->statement->execute($db, $args);
    }

    private function getInFunc()
    {
        preg_match("/\inFunc:\s*(\S+)/", $this->modline, $m);
        $f = isset($m[1]) ? $m[1] : null;

        if ($f && !is_callable($f)) {
            throw new \Nulpunkt\Yesql\Exception\MethodMissing("{$f} is not callable");
        }

        return $f;
    }
}
