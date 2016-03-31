-- name: getById oneOrMany: one
-- This will make getById a method which fetch a test row from the database
select * from test_table where id = ?

-- name: getObjectByIdManually oneOrMany: one rowFunc: TestHelper\TestObject::fromRow
select * from test_table where id = ?

-- name: getObjectByIdAutomagically oneOrMany: one rowClass: TestHelper\TestObject
select * from test_table where id = ?

-- name: getByIdMapped oneOrMany: one inFunc: TestHelper\TestObject::mappedParams
select * from test_table where id = :id

-- name: getAllIds
select id from test_table;

-- name: insertRow
insert into test_table (something) values (?)

-- name: insertObject inFunc: TestHelper\TestObject::toRow
insert into test_table (id, something) values (:id, :something)

-- name: updateRow
update test_table set something = ?
where id = ?

-- name: updateObject inFunc: TestHelper\TestObject::toRow
update test_table set something = :something
where id = :id

-- name: deleteById
DELETE FROM test_table WHERE id = ?;
