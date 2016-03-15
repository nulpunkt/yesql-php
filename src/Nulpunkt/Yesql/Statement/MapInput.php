<?php

namespace Nulpunkt\Yesql\Statement;

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
