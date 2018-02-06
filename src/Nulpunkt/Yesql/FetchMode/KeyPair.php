<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

class KeyPair extends BaseFetchMode
{
    public function setMode(PDOStatement $stmt)
    {
        $stmt->setFetchMode(\PDO::FETCH_KEY_PAIR);
    }
}