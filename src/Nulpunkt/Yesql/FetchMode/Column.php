<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

class Column extends BaseFetchMode
{
    /** @var int */
    private $column;

    public function setArgument($argument)
    {
        $this->column = $argument ?: 0;
    }

    public function setMode(PDOStatement $stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_COLUMN, $this->column);
    }
}