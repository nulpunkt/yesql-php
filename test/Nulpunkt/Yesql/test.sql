-- name: getById oneOrMany: one
select * from test_table where id = :id

-- name: getObjectById oneOrMany: one rowFunc: TestHelper\TestObject::fromRow
select * from test_table where id = :id

-- name: getAllIds
select id from test_table;

-- name: insertRow
insert into test_table (something) values (:something)

-- name: insertObject inFunc: ->toRow
insert into test_table (id, something) values (:id, :something)

-- name: insertManyObjects inFunc: ->toRow oneOrMany: many
insert into test_table (id, something) values (:id, :something)

-- name: updateRow
update test_table set something = :something
where id = :id

-- name: updateObject inFunc: ->toRow
update test_table set something = :something
where id = :id

-- name: deleteById
DELETE FROM test_table WHERE id = :id;
