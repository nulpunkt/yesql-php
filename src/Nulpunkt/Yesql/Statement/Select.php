<?php

namespace Nulpunkt\Yesql\Statement;

class Select implements Statement
{
    private $sql;
    private $modline;
    private $rowFunc;
    private $stmt;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
        $this->modline = $modline;
        $this->rowFunc = $this->getRowFunc();
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

        if ($this->oneOrMany() == 'one') {
            return $this->prepareElement($this->stmt->fetch(\PDO::FETCH_ASSOC));
        } else {
            return array_map([$this, 'prepareElement'], $this->stmt->fetchAll(\PDO::FETCH_ASSOC));
        }
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
