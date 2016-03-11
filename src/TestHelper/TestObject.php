<?php

namespace TestHelper;

class TestObject
{
    public static function fromRow($row)
    {
        return new self($row);
    }
}
