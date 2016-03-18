<?php

namespace TestHelper;

class TestObject
{
    public $id;

    public static function fromRow($row)
    {
        return new self($row);
    }

    public static function toRow($i)
    {
        return ['id' => $i->id, 'something' => 'from object'];
    }
}
