<?php

namespace Nulpunkt\Yesql;

class RepositoryTest extends \TestHelper\TestCase
{
    public function testWeCanGetOneRow()
    {
        $this->assertEquals(['id' => 1, 'something' => 'a thing'], $this->repo->getById(1));
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

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table order by id desc limit 1"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => $lastInsertId, 'something' => 'new thing']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
    }

    public function testWeCanInsertAnObject()
    {
        $o = new \TestHelper\TestObject;
        $lastInsertId = $this->repo->insertObject($o);

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table order by id desc limit 1"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => $lastInsertId, 'something' => 'from object']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
    }

    public function testWeCanUpdate()
    {
        $rowsAffected = $this->repo->updateRow('other thing updated', 2);

        $this->assertSame(1, $rowsAffected);

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table where id = 2"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => 2, 'something' => 'other thing updated']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
    }

    public function testWeCanUpdateWithObject()
    {
        $o = new \TestHelper\TestObject;
        $o->id = 2;
        $rowsAffected = $this->repo->updateObject($o);

        $this->assertSame(1, $rowsAffected);

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table where id = 2"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => 2, 'something' => 'from object']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
    }

    public function testWeCanDelete()
    {
        $this->repo->deleteById(1);

        $dataSet = $this->createQueryDataset(
            ['t' => 'SELECT * FROM test_table WHERE id = 1']
        );

        $this->assertSame(
            0,
            $dataSet->getTable('t')->getRowCount(),
            'The row should be gone from the database'
        );
    }

    /**
     * @expectedException Nulpunkt\Yesql\Exception\MethodMissing
     */
    public function testWeComplainAboutUndefinedMethods()
    {
        $this->repo->derp();
    }

    /**
     * @expectedException Nulpunkt\Yesql\Exception\UnknownStatement
     */
    public function testWeComplainAboutSqlWeDontKnowWhatToDoAbout()
    {
        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_statement.sql");
        $r->describeSomething();
    }

    /**
     * @expectedException Nulpunkt\Yesql\Exception\MethodMissing
     */
    public function testWeComplainAboutNonExsistingRowFunc()
    {
        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_rowfunc.sql");
        $r->describeSomething();
    }

    /**
     * @expectedException Nulpunkt\Yesql\Exception\ClassNotFound
     */
    public function testWeComplainAboutNonExsistingRowClass()
    {
        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown_rowclass.sql");
        $r->describeSomething();
    }

    public function setup()
    {
        parent::setup();
        $this->repo = new Repository($this->getDatabase(), __DIR__ . "/test.sql");
    }

    protected function getDataSet()
    {
        return $this->createArrayDataSet(
            [
                'test_table' => [
                    ['id' => 1, 'something' => 'a thing'],
                    ['id' => 2, 'something' => 'an other thing!'],
                ]
            ]
        );
    }
}
