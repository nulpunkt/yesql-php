<?php

namespace Nulpunkt\Yesql\Statement;

class SelectTest extends \TestHelper\UnitTestCase
{
    /**
     * @expectedException \Nulpunkt\Yesql\Exception\MethodMissing
    */
    public function testWeComplainIfRowFuncDoesNotExsist()
    {
        new Select(null, 'rowFunc: sntaoheu');
    }
}
