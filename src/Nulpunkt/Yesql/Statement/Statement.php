<?php

namespace Nulpunkt\Yesql\Statement;

interface Statement
{
    public function execute($db, $args);
}
