-- name: getById
select * from test_table where id = :id

-- name: getAllIds
select id from test_table;

-- name: insertRow
insert into test_table (something) values (:something)

-- name: updateRow
update test_table set something = :something where id = :id
