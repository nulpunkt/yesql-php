<?php

namespace Nulpunkt\Yesql\FetchMode;

use Nulpunkt\Yesql\Exception\UnknownFetchMode;

class Factory implements FetchModeFactory
{
    public function createFromModLine($modLine)
    {
        list($mode, $argument) = $this->processModLine($modLine);

        $fetchModes = [
            'assoc' => Assoc::class,
            'class' => FetchClass::class,
            'column' => Column::class,
            'keypair' => KeyPair::class,
            'named' => Named::class,
        ];

        if (!isset($fetchModes[$mode])) {
            throw new UnknownFetchMode(
                "{$mode} is not a supported fetchMode."
            );
        }

        /** @var \Nulpunkt\Yesql\FetchMode\FetchMode $fetchMode */
        $fetchMode = new $fetchModes[$mode];
        $fetchMode->setArgument($argument);

        return $fetchMode;
    }

    protected function processModLine($modLine)
    {
        $fetchMode = 'assoc';
        $argument = null;

        // Backwards compatibility
        if (preg_match('/rowClass:\s*(\S+)/', $modLine, $m)) {
            $fetchMode = 'class';
            $argument = @$m[1];
        }

        if (preg_match($this->getFetchModeRegex(), $modLine, $m)) {
            $fetchMode = isset($m[1]) ? $m[1] : 'assoc';
            $argument = isset($m[3]) ? $m[3] : null;
        }

        return [strtolower($fetchMode), $argument];
    }

    protected function getFetchModeRegex()
    {
        return '/fetchMode:\s*(\w+)(\((.*?)\))?/';
    }
}