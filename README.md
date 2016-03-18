# yesql-php [![Build Status](https://travis-ci.org/nulpunkt/yesql-php.png?branch=master)](https://travis-ci.org/nulpunkt/yesql-php)

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
$r->getAllRows();
```

A database without rows is not of much use, lets insert some data:
```sql
-- name: insertRow
insert into test_table (something) values (?)

```
```php
// returns the insertId
$r->insertRow('a thing');
```

As default, yesql will simply bind all params passed to the called function, to
the query associated with it. We'll see how to make mappers further down.

Maybe we need to fix some exsisting data
```sql
-- name: updateRow
update test_table set something = ? where id = ?

```
```php
// returns the number of rows touched by the update
$r->updateRow('fixed thing', 3);
```

yesql-php support different modlines, lets say we know we only need to get one
row:

```sql
-- name: getById oneOrMany: one
select * from test_table where id = ?;
```
```php
// Fetches one row with id 3
$r->getById(3);
```

Maybe we want to return an object instead of just the row. By specifying a
rowFunc, we can have a function called, on every row returned:

```sql
-- name: getObjectById oneOrMany: one rowFunc: MyObject::fromRow
select * from test_table where id = ?
```
```php
class MyObject {
  public static function fromRow($r) {
    return new self($r['id'], $r['something']);
  }
}
// return one instance of MyObject
$r->getObjectById(3);
```

Maybe we have a class with a `toRow` method we'd like to call on insert
```sql
-- name: insertObject inFunc: MyObject::toRow
insert into test_table (id, something) values (:id, :something)
```
```php
class MyObject {
  // $i will be the arguments passed to insertObject
  public static function toRow($i, $o) {
    return ['id' => $i, 'something' => $o->something];
  }
}
$o = new MyObject;
$r->insertObject($i, $o) 
```

## Todo

 * Travis tests to make sure we work on different db
 * Moar niceness
 * Packist
