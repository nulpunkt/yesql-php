<?php

namespace Nulpunkt\Yesql\FetchMode;

use Nulpunkt\Yesql\Exception\ClassNotFound;
use PDOStatement;

class FetchClass extends BaseFetchMode
{
    /** @var string */
    private $className;

    public function setArgument($argument)
    {
        if (!class_exists($argument)) {
            throw new ClassNotFound("{$argument} is not a class");
        }
        $this->className = $argument;
    }

    public function setMode(PDOStatement $stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->className);
    }
}