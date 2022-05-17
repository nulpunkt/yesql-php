<?php

namespace Nulpunkt\Yesql\Statement;

class MapInputTest extends \PHPUnit\Framework\TestCase
{
    public function testWeCanExecuteAStatement()
    {
        $s = $this->createMock('Nulpunkt\Yesql\Statement\Statement');
        $s->expects($this->once())->method('execute')
            ->with('db', ['id' => 3]);

        $m = new MapInput($s, '', []);
        $m->execute('db', ['id' => 3]);
    }

    public function testWeCanExecuteAStatementWithNamedParams()
    {
        $s = $this->createMock('Nulpunkt\Yesql\Statement\Statement');
        $s->expects($this->once())->method('execute')
            ->with('db', ['id' => 3]);

        $m = new MapInput($s, '', ['id']);
        $m->execute('db', [3]);
    }

    public function testWeCanExecuteAStatementWithInFunc()
    {
        $o = new \TestHelper\TestObject();
        $o->id = 3;
        $modline = 'inFunc: \TestHelper\TestObject::toRow';

        $s = $this->createMock('Nulpunkt\Yesql\Statement\Statement');
        $s->expects($this->once())->method('execute')
            ->with('db', ['id' => 3, 'something' => 'from object']);

        $m = new MapInput($s, $modline, []);
        $m->execute('db', [$o]);
    }

    public function testWeComplainIfInFuncIsNotCallable()
    {
        $this->expectException(\Nulpunkt\Yesql\Exception\MethodMissing::class);

        $modline = 'inFunc: nope.exe';
        new MapInput(null, $modline, []);
    }
}
