<?php

namespace Nulpunkt\Yesql\FetchMode;

use PDOStatement;

interface FetchMode
{
    /**
     * The optional argument from the modline
     * For example:
     *   -- name: getFoo fetchMode: class(foobar)
     *   This modline would have foobar as argument
     *
     * @param string $argument
     * @return void
     */
    public function setArgument($argument);

    /**
     * Applies the fetchMode to the PDOStatement
     *
     * @param \PDOStatement $stmt
     * @return void
     */
    public function setMode(PDOStatement $stmt);
}