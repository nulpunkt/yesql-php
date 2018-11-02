<?php

namespace Nulpunkt\Yesql\Statement;

class Method
{
    private $name;
    private $argNames;

    public function __construct($name, $argNames)
    {
        $this->name = $name;
        $this->argNames = array_filter(array_map('trim', explode(',', $argNames)));
    }

    public function getName()
    {
        return $this->name;
    }

    public function getArgNames()
    {
        return $this->argNames;
    }
}
