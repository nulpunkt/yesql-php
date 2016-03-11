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
