<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

class Assoc extends BaseFetchMode
{
    public function setMode(PDOStatement $stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
    }
}