<?php

namespace Nulpunkt\Yesql\Statement;

use Nulpunkt\Yesql\Exception\UnknownStatement;
use Nulpunkt\Yesql\FetchMode\FetchModeFactory;

class Factory
{
    /** @var \Nulpunkt\Yesql\FetchMode\FetchModeFactory */
    protected $fetchModeFactory;

    public function __construct(FetchModeFactory $fetchModeFactory)
    {
        $this->fetchModeFactory = $fetchModeFactory;
    }

    public function createStatements($sqlFile)
    {
        $collected = [];
        foreach (file($sqlFile) as $line) {
            $isComment = strpos($line, '--') === 0;
            if ($isComment && ($name = $this->getMethodName($line))) {
                $collected[] = $currentCollector = new Collector($name, $line);
            } elseif (!$isComment) {
                $currentCollector->appendSql($line);
            }
        }

        $statements = [];
        foreach ($collected as $c) {
            $statements[$c->getMethodName()] = new MapInput(
                $this->createStatement($c->getSql(), $c->getModline()),
                $c->getModline()
            );
        }

        return $statements;
    }

    public function getMethodName($line)
    {
        preg_match("/\bname:\s*(\S+)/", $line, $m);
        return isset($m[1]) ? $m[1] : null;
    }

    private function createStatement($collectedSql, $modline)
    {
        if (stripos($collectedSql, 'select') === 0) {
            $fetchMode = $this->fetchModeFactory->createFromModLine($modline);
            return new Select($collectedSql, $modline, $fetchMode);
        } elseif (stripos($collectedSql, 'insert') === 0) {
            return new Insert($collectedSql, $modline);
        } elseif (stripos($collectedSql, 'update') === 0 || stripos($collectedSql, 'delete') === 0) {
            return new Update($collectedSql, $modline);
        } else {
            throw new UnknownStatement($collectedSql);
        }
    }
}
