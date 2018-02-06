<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

class Named extends BaseFetchMode
{
    public function setMode(PDOStatement $stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_NAMED);
    }
}