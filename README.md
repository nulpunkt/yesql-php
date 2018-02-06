# yesql-php [![Build Status](https://travis-ci.org/nulpunkt/yesql-php.png?branch=master)](https://travis-ci.org/nulpunkt/yesql-php)

This is a clone of the wonderful [yesql library from clojure](https://github.com/krisajenkins/yesql).
The idea is to have a seperate sql file for queries, which you can then access
as methods on a class.

I built for fun on a Friday, liked it a lot and now we are using it where I work. So I guess it's production ready.

## Installation
Use composer to require:
```
"nulpunkt/yesql-php": "^1"
```

# Examples!

You need to make a repository of queries:

```php
$pdo = new PDO($host, $user, $pass); // Fill in the blanks
$r = new Nulpunkt\Yesql\Repository($pdo, "my-queries/queries.sql");
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

Or we want to define the PDO fetch mode. (http://php.net/manual/en/pdo.constants.php#pdo.constants.fetch-lazy)

The available fetchmodes are: `assoc`, `class`, `column`, `keypair`, `named`
```sql
-- name: getKeyed fetchMode: keypair
select id, something from test_table
-- name: getColumn fetchMode: column
select id from test_table
-- name: getObjectById oneOrMany: one fetchMod: class(MyObject)
select * from test_table where id = ?
```
```php
// returns an array with the id column as key and something column as value
$r->getKeyed();
// returns a one-dimensional array of ids
$r->getColumn();
// returns one row, which is an instance of MyObject with id and something set
class MyObject {
}
$r->getObjectById(3);
```

Maybe we want to return a modified version of the row. By specifying a
rowFunc, we can have a function called, on every row returned:

```sql
-- name: getMappedById oneOrMany: one rowFunc: MyObject::mapRow
select * from test_table where id = ?
```
```php
class MyObject {
  public static function mapRow($r) {
    return ['id' => $r['id'], 'ohwow' => $r['something']];
  }
}
// return one row, with keys id and ohwow
$r->getMappedById(3);
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
