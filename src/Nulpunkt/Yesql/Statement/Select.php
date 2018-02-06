<?php

namespace Nulpunkt\Yesql\Statement;

use Nulpunkt\Yesql\FetchMode\FetchMode;

class Select implements Statement
{
    private $sql;
    private $modline;
    private $rowFunc;
    private $stmt;
    /** @var \Nulpunkt\Yesql\FetchMode\FetchMode  */
    private $fetchMode;

    public function __construct($sql, $modline, FetchMode $fetchMode)
    {
        $this->sql = $sql;
        $this->modline = $modline;
        $this->rowFunc = $this->getRowFunc();
        $this->fetchMode = $fetchMode;
    }

    public function execute($db, $args)
    {
        if (!$this->stmt) {
            $this->stmt = $db->prepare($this->sql);
        }

        if (isset($args)) {
            $this->stmt->execute($args);
        } else {
            $this->stmt->execute();
        }

        $this->fetchMode->setMode($this->stmt);

        $res = array_map([$this, 'prepareElement'], $this->stmt->fetchAll());

        return $this->oneOrMany() == 'one' ? @$res[0] : $res;
    }

    private function oneOrMany()
    {
        preg_match("/\boneOrMany:\s*(one|many)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : "many";
    }

    private function getRowFunc()
    {
        preg_match('/rowFunc:\s*(\S+)/', $this->modline, $m);
        $f = isset($m[1]) ? $m[1] : [$this, 'identity'];

        if ($f && !is_callable($f)) {
            throw new \Nulpunkt\Yesql\Exception\MethodMissing("{$f} is not callable");
        }

        return $f;
    }

    private function prepareElement($res)
    {
        return call_user_func($this->rowFunc, $res);
    }

    private function identity($e)
    {
        return $e;
    }
}
