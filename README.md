# yesql-php

I'm trying to build a clone of the wonderful yesql library from clojure. This
is very much work in progress.

The idea is to have a seperate sql file for queries, which you can then access
as methods on a class.

# Examples!

You need to make a repository of queries:

```php
$pdo = new PDO($host, $user, $pass); // Fill in the blanks
$r = new Nulpunkt\Yesql\Repository($pdo, "my-queries/user.sql");
```
in `queries.sql` we can put:

```sql
-- name: getAllRows
-- This will fetch all rows from test_table
select * from test_table;
```
which will allow us to call

```php
$r->getAllRows()
```

A database without rows is not of much use, lets insert some data:
```sql
-- name: insertRow
insert into test_table (something) values (:something)

```
```php
// returns the insertId
$r->insertRow(['something' => 'a thing']) 
```

Maybe we need to fix some exsisting data
```sql
-- name: updateRow
update test_table set something = :something where id = :id

```
```php
// returns the number of rows touched by the update
$r->insertRow(['id' => 3, 'something' => 'fixed thing']) 
```

yesql-php support different modlines, lets say we know we only need to get one
row:

```sql
-- name: getById oneOrMany: one
select * from test_table where id = :id;
```
```php
// Fetches one row with id 3
$r->getById(['id' => 3]) 
```

Maybe we want to return an object instead of just the row. By specifying a
rowFunc, we can have a function called, on every row returned:

```sql
-- name: getObjectById oneOrMany: one rowFunc: MyObject::fromRow
select * from test_table where id = :id
```
```php
class MyObject {
  public fromRow($r) {
    return new self($r['id'], $r['something']);
  }
}
// return one instance of MyObject
$r->getObjectById(['id' => 3]) 
```


Maybe we have a class with a `toRow` method we'd like to call on insert
```sql
-- name: insertObject inFunc: ->toRow
insert into test_table (id, something) values (:id, :something)
```
```php
class MyObject {
  public toRow() {
    return ['id' => $this->id, 'something' => $this->something];
  }
}
$o = new MyObject;
// calls $o->toRow() and saves the returned value to the database
$r->insertObject($o) 
```

## Todo

 * Travis tests to make sure we work on different db
 * Moar niceness
 * Packist
