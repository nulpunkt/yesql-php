-- name: getById
select * from test_table where id = :id

-- name: getAllIds
select id from test_table;
