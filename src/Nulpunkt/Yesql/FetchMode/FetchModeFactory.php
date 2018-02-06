<?php

namespace Nulpunkt\Yesql\FetchMode;

interface FetchModeFactory
{
    /**
     * Creates a FetchMode based on the modline
     *
     * @param string $modLine
     * @return \Nulpunkt\Yesql\FetchMode\FetchMode
     */
    public function createFromModLine($modLine);
}