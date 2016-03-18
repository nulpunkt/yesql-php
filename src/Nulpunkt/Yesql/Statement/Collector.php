<?php

namespace Nulpunkt\Yesql\Statement;

class Collector
{
    private $methodName;
    private $modline;
    private $sql;

    public function __construct($methodName, $modline)
    {
        $this->methodName = $methodName;
        $this->modline = $modline;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function getModline()
    {
        return $this->modline;
    }

    public function appendSql($sql)
    {
        $this->sql .= $sql;
    }
}
