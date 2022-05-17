# yesql-php

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

## Fetching many rows
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

## Inserting a row
A database without rows is not of much use, lets insert some data:
```sql
-- name: insertRow(thing)
insert into test_table (something) values (:thing)

```
```php
// returns the insertId
$r->insertRow('a thing');
```

As default, yesql will simply bind all params passed to the called function, to
the query associated with it. We'll see how to make mappers further down.

## Updating a row
Maybe we need to fix some exsisting data
```sql
-- name: updateRow(id, thing)
update test_table set something = :thing where id = :id

```
```php
// returns the number of rows touched by the update
$r->updateRow(3, 'fixed thing');
```

## Fetching a single row
yesql-php support different modlines, lets say we know we only need to get one
row:

```sql
-- name: getById(id) oneOrMany: one
select * from test_table where id = :id;
```
```php
// Fetches one row with id 3
$r->getById(3);
```

## Fetching and mapping rows in one go
Maybe we want to return a modified version of the row. By specifying a
rowFunc, we can have a function called, on every row returned:

```sql
-- name: getMappedById(id) oneOrMany: one rowFunc: MyObject::mapRow
select * from test_table where id = :id
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

## Mapping rows to objects
Sometimes an object is want you want, rowClass got your back:

```sql
-- name: getObjectById(id) oneOrMany: one rowClass: MyObject
select * from test_table where id = :id
```
```php
class MyObject {
}
// return one row, which is an instance of MyObject with id and something set
$r->getObjectById(3);
```

## Mapping data on the way in
We may need to map our domain objects to be able to insert them into the
database.
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
