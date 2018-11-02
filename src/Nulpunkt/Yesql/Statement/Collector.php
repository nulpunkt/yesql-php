<?php

namespace Nulpunkt\Yesql\Statement;

class Collector
{
    private $method;
    private $modline;
    private $sql;

    public function __construct($method, $modline)
    {
        $this->method = $method;
        $this->modline = $modline;
    }

    public function getMethodName()
    {
        return $this->method->getName();
    }

    public function getArgNames()
    {
        return $this->method->getArgNames();
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
