<?php

namespace TestHelper;

class TestObject
{
    public $id;

    public static function fromRow($row)
    {
        return new self($row);
    }

    public function toRow()
    {
        return ['id' => $this->id, 'something' => 'from object'];
    }
}
