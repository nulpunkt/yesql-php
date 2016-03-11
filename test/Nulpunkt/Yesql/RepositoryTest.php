<?php

namespace Nulpunkt\Yesql;

class RepositoryTest extends \TestHelper\TestCase
{
    public function testWeCanSelect()
    {
        $this->assertSame([['id' => 1, 'something' => 'a thing']], $this->repo->getById(['id' => 1]));
    }

    public function setup()
    {
        $this->repo = new Repository(
            $this->getDatabase(),
            __DIR__ . "/test.sql"
        );
    }

    protected function getDataSet()
    {
        return $this->createArrayDataSet(
            [
            ]
        );
    }
}
