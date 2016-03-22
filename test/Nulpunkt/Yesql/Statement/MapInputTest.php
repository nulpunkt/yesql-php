<?php

namespace Nulpunkt\Yesql\Statement;

class MapInputTest extends \PHPUnit_Framework_TestCase
{
    public function testWeCanExecuteAStatement()
    {
        $s = $this->getMock('Nulpunkt\Yesql\Statement\Statement');
        $s->expects($this->once())->method('execute')
            ->with('db', ['id'=> 3]);

        $m = new MapInput($s, '');
        $m->execute('db', ['id' => 3]);
    }

    public function testWeCanExecuteAStatementWithInFunc()
    {
        $o = new \TestHelper\TestObject;
        $o->id = 3;
        $modline = 'inFunc: \TestHelper\TestObject::toRow';

        $s = $this->getMock('Nulpunkt\Yesql\Statement\Statement');
        $s->expects($this->once())->method('execute')
            ->with('db', ['id'=> 3, 'something' => 'from object']);

        $m = new MapInput($s, $modline);
        $m->execute('db', [$o]);
    }

    /**
     * @expectedException \Nulpunkt\Yesql\Exception\MethodMissing
     */
    public function testWeComplainIfInFuncIsNotCallable()
    {
        $modline = 'inFunc: nope.exe';
        new MapInput(null, $modline);
    }
}
