<?php

namespace Nulpunkt\Yesql;

class MapInput
{
    private $modline;

    public function __construct($modline)
    {
        $this->modline = $modline;
    }

    public function map($i)
    {
        $inFunc = $this->getInFunc();
        if ($inFunc) {
            return call_user_func_array($inFunc, $i);
        }

        return $i;
    }

    private function getInFunc()
    {
        preg_match("/\inFunc:\s*(\S+)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : null;
    }
}
