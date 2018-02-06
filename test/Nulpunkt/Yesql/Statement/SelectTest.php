<?php

namespace Nulpunkt\Yesql\Statement;

use Nulpunkt\Yesql\FetchMode\Assoc;
use Nulpunkt\Yesql\FetchMode\Factory;

class SelectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Nulpunkt\Yesql\Exception\MethodMissing
    */
    public function testWeComplainIfRowFuncDoesNotExsist()
    {
        new Select(null, 'rowFunc: sntaoheu', new Assoc);
    }

    /**
     * @expectedException \Nulpunkt\Yesql\Exception\UnknownFetchMode
     */
    public function testWeComplainIfFetchModeIsUnknown()
    {
        (new Factory)->createFromModLine('fetchMode: sntaoheu');
    }
}
