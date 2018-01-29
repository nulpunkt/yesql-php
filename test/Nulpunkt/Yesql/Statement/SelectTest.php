<?php

namespace Nulpunkt\Yesql\Statement;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Nulpunkt\Yesql\Exception\MethodMissing
    */
    public function testWeComplainIfRowFuncDoesNotExsist()
    {
        new Select(null, 'rowFunc: sntaoheu');
    }

    /**
     * @expectedException \Nulpunkt\Yesql\Exception\UnknownFetchMode
     */
    public function testWeComplainIfFetchModeIsUnknown()
    {
        new Select(null, 'fetchMode: sntaoheu');
    }
}
