<?php

namespace Nulpunkt\Yesql;

class RepositoryTest extends \TestHelper\TestCase
{
    public function testWeCanSelect()
    {
        $this->assertEquals([['id' => 1, 'something' => 'a thing']], $this->repo->getById(['id' => 1]));
        $this->assertEquals([['id' => 1], ['id' => 2]], $this->repo->getAllIds());
    }

    public function testWeCanInsert()
    {
        $lastInsertId = $this->repo->insertRow(['something' => 'new thing']);

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table order by id desc limit 1"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => $lastInsertId, 'something' => 'new thing']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
    }

    public function testWeCanUpdate()
    {
        $rowsAffected = $this->repo->updateRow(['something' => 'other thing updated', 'id' => 2]);

        $this->assertSame(1, $rowsAffected);

        $dataSet = $this->createQueryDataset(
            ['t' => "SELECT * FROM test_table where id = 2"]
        );

        $expectedData = $this->createArrayDataSet(
            ['t' => [['id' => 2, 'something' => 'other thing updated']]]
        );

        $this->assertDataSetsEqual($expectedData, $dataSet);
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
        $r = new Repository($this->getDatabase(), __DIR__ . "/unknown.sql");
        $r->describeSomething();
    }

    public function setup()
    {
        $this->repo = new Repository($this->getDatabase(), __DIR__ . "/test.sql");
    }

    protected function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }
}
