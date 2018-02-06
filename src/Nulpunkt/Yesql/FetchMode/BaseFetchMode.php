<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

abstract class BaseFetchMode implements FetchMode
{
    public function setArgument($argument){}

    abstract public function setMode(PDOStatement $stmt);
}