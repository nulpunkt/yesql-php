<?php

namespace Nulpunkt\Yesql\Statement;

use Nulpunkt\Yesql\Exception\UnknownStatement;

class Factory
{
    public function createStatements($sqlFile)
    {
        $collected = [];
        foreach (file($sqlFile) as $line) {
            $isComment = strpos($line, '--') === 0;
            if ($isComment && ($method = $this->getMethod($line))) {
                $collected[] = $currentCollector = new Collector($method, $line);
            } elseif (!$isComment) {
                $currentCollector->appendSql($line);
            }
        }

        $statements = [];
        foreach ($collected as $c) {
            $statements[$c->getMethodName()] = new MapInput(
                $this->createStatement($c->getSql(), $c->getModline()),
                $c->getModline(),
                $c->getArgNames()
            );
        }

        return $statements;
    }

    public function getMethod($line)
    {
        preg_match("/\bname:\s*([^\s(]+)\s*(\(([\w,\s]+)\))?/", $line, $m);
        return isset($m[1]) ? new Method($m[1], isset($m[3]) ? $m[3] : '') : null;
    }

    private function createStatement($collectedSql, $modline)
    {
        if (stripos($collectedSql, 'select') === 0) {
            return new Select($collectedSql, $modline);
        } elseif (stripos($collectedSql, 'insert') === 0) {
            return new Insert($collectedSql, $modline);
        } elseif (stripos($collectedSql, 'update') === 0 || stripos($collectedSql, 'delete') === 0) {
            return new Update($collectedSql, $modline);
        } else {
            throw new UnknownStatement($collectedSql);
        }
    }
}
