<?php

namespace Nulpunkt\Yesql\Statement;

class Select
{
    private $sql;
    private $modline;
    private $rowFunc;

    public function __construct($sql, $modline)
    {
        $this->sql = $sql;
        $this->modline = $modline;
    }

    public function execute($db, $args)
    {
        $stmt = $db->prepare($this->sql);
        if (isset($args)) {
            $stmt->execute($args);
        } else {
            $stmt->execute();
        }

        $this->setRowFunc();
        if ($this->oneOrMany() == 'one') {
            return $this->prepareElement($stmt->fetch(\PDO::FETCH_ASSOC));
        } else {
            return array_map([$this, 'prepareElement'], $stmt->fetchAll(\PDO::FETCH_ASSOC));
        }
    }

    private function oneOrMany()
    {
        preg_match("/\boneOrMany:\s*(one|many)/", $this->modline, $m);
        return isset($m[1]) ? $m[1] : "many";
    }

    private function setRowFunc()
    {
        preg_match('/rowFunc:\s*(\S+)/', $this->modline, $m);
        $this->rowFunc = isset($m[1]) ? $m[1] : [$this, 'identity'];
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
