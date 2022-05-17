<?php

namespace Nulpunkt\Yesql;

class RepositoryTest extends \TestHelper\TestCase
{
    public function testWeCanGetOneRow()
    {
        $this->assertEquals(['id' => 1, 'something' => 'a thing'], $this->repo->getById(1));
    }

    public function testWeCanGetOneRowWithNamedParam()
    {
        $this->assertEquals(['id' => 1, 'something' => 'a thing'], $this->repo->getByIdNamed(1));
    }

    public function testWeCanGetARowThatDoesNotExsist()
    {
        $this->assertNull($this->repo->getById(11));
    }

    public function testWeCanGetOneRowIntoAnObjectManually()
    {
        $this->assertInstanceOf('TestHelper\TestObject', $this->repo->getObjectByIdManually(1));
    }

    public function testWeCanGetOneRowIntoAnObjectAutomagically()
    {
        $this->assertInstanceOf('TestHelper\TestObject', $this->repo->getObjectByIdAutomagically(1));
    }

    public function testWeCanMapParamsInSelect()
    {
        $this->assertEquals(['id' => 1, 'something' => 'a thing'], $this->repo->getByIdMapped(1));
    }

    public function testWeCanGetManyRows()
    {
        $this->assertEquals([['id' => 1], ['id' => 2]], $this->repo->getAllIds());
    }

    public function testWeCanInsert()
    {
        $lastInsertId = $this->repo->insertRow('new thing');

        $this->assertQueryEquals(
            [['id' => $lastInsertId, 'something' => 'new thing']],
            "SELECT * FROM test_table order by id desc limit 1"
        );
    }

    public function testWeCanInsertAnObject()
    {
        $o = new \TestHelper\TestObject();
        $lastInsertId = $this->repo->insertObject($o);

        $this->assertQueryEquals(
            [['id' => $lastInsertId, 'something' => 'from object']],
            "SELECT * FROM test_table order by id desc limit 1"
        );
    }

    public function testWeCanUpdate()
    {
        $rowsAffected = $this->repo->updateRow('other thing updated', 2);

        $this->assertSame(1, $rowsAffected);

        $this->assertQueryEquals(
            [['id' => 2, 'something' => 'other thing updated']],
            "SELECT * FROM test_table where id = 2"
        );
    }

    public function testWeCanUpdateWithNamedParams()
    {
        $rowsAffected = $this->repo->updateRowNamed(2, 'other thing updated');

        $this->assertSame(1, $rowsAffected);

        $this->assertQueryEquals(
            [['id' => 2, 'something' => 'other thing updated']],
            "SELECT * FROM test_table where id = 2"
        );
    }

    public function testWeCanUpdateWithObject()
    {
        $o = new \TestHelper\TestObject();
        $o->id = 2;
        $rowsAffected = $this->repo->updateObject($o);

        $this->assertSame(1, $rowsAffected);

        $this->assertQueryEquals(
            [['id' => 2, 'something' => 'from object']],
            "SELECT * FROM test_table where id = 2"
        );
    }

    public function testWeCanDelete()
    {
        $this->repo->deleteById(1);

        $this->assertQueryEquals([], "SELECT * FROM test_table where id = 1");
    }

    public function testWeComplainAboutUndefinedMethods()
    {
        $this->expectException(Exception\MethodMissing::class);

        $this->repo->derp();
    }

    public function testWeComplainAboutSqlWeDontKnowWhatToDoAbout()
    {
        $this->expectException(Exception\UnknownStatement::class);

        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_statement.sql");
        $r->describeSomething();
    }

    public function testWeComplainAboutNonExsistingRowFun()
    {
        $this->expectException(Exception\MethodMissing::class);

        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_rowfunc.sql");
        $r->describeSomething();
    }

    public function testWeComplainAboutNonExsistingRowClass()
    {
        $this->expectException(Exception\ClassNotFound::class);

        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_rowclass.sql");
        $r->describeSomething();
    }

    public function setup(): void
    {
        parent::setup();
        $this->repo = new Repository($this->getDatabase(), __DIR__ . "/test.sql");
    }

    protected function getDataSet(): array
    {
        return [
            'test_table' => [
                ['id' => 1, 'something' => 'a thing'],
                ['id' => 2, 'something' => 'an other thing!'],
            ]
        ];
    }
}
