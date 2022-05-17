<?php

namespace Nulpunkt\Yesql\Statement;

class SelectTest extends \PHPUnit\Framework\TestCase
{
    public function testWeComplainIfRowFuncDoesNotExsist()
    {
        $this->expectException(\Nulpunkt\Yesql\Exception\MethodMissing::class);

        new Select(null, 'rowFunc: sntaoheu');
    }
}
