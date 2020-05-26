# Dokumentasi Query Builder PHP
Membaca dokumentasi sangat disarankan agar tidak terjadi kesalahan saat menggunakannya.

## Instalasi
Ada beberapa cara menginstall yang bisa di ikuti dibawah ini.

Membuat file `composer.json`
```json
{
    "require": {
        "hilyahtech/query-builder-php": "^1.0"
    }
}
```

Kemudian lakukan instalasi dengan printah.
```sh
composer install
```

Atau Anda bisa menginstall secara langsung.
```sh
composer require hilyahtech/query-builder-php
```

## Cara Cepat
```php
require 'vendor/autoload.php';

$config = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'test',
    'username' => 'root',
    'password' => ''
];

$db = new \HilyahTech\QueryBuilder\DB($config);
```

# Penggunakan Methods

## Config
```php
$config = [
    # Database driver (optional)
    # Default value: mysql
    # values: mysql
    'driver' => 'mysql',

    # Host name and IP Address (optional)
    # Default value: localhost
    'host' => 'localhost',

    # Database name (require)
    'database' => 'test',

    # Database username (require)
    'username' => 'root',

    # Database password (require)
    'password' => ''
];

$db = new \HilyahTech\QueryBuilder\DB($config);
```

## Daftar Isi

* [select](#select)
* [select functions (min, max, count, avg, sum)](#select-functions-min-max-count-avg-sum)
* [table](#table)
* [get and first](#get-and-first)
* [join](#join)
* [where](#where)
* [where in](#where-in)
* [between](#between)
* [like](#like)
* [groupBy](#groupby)
* [having](#having)
* [orderBy](#orderby)
* [insert](#insert)
* [update](#update)
* [delete](#delete)

## Methods

### select
```php
$db->table('users')->select('name, email');
# sql: "SELECT name, email FROM users"

$db->table('users')->select(['name', 'email']);
# sql: "SELECT name, email FROM users"
```

### select functions (min, max, count, avg, sum)
```php
$db->table('users')->max('follows');
# sql: "SELECT max(follows) FROM users"

$db->table('users')->sum('star');
# sql: "SELECT sum(star) FROM users"
```

### table
```php
$db->table('users');
# sql: "SELECT * FROM users"

$db->table('users, roles');
# sql: "SELECT * FROM users, roles"

$db->table(['users', 'roles']);
# sql: "SELECT * FROM users, roles"

$db->table('users AS user');
# sql: "SELECT * FROM users AS user"
```

### get and first
```php
# get -> menampilkan semuanya
# first -> menampilkan 1

$db->table('users')->get();
# sql: "SELECT * FROM users"

$db->table('users')->first();
# sql: "SELECT * FROM users LIMIT 1"
```

### join
Ada 7 Method join

* join()
* leftJoin()
* rightJoin()
* leftOuterJoin()
* rightOuterJoin()
* fullOuterJoin()

```php
$db->table('test')->join('check', 'test.id', 'check.id')->get();
# sql: "SELECT * FROM test JOIN check ON test.id = check.id"

$db->table('test')->leftJoin('check', 'test.id', 'check.id')->get();
# sql: "SELECT * FROM test LEFT JOIN check ON test.id = check.id"

$db->table('test')->fullOuterJoin('check', 'test.id', '=', 'check.id')->get();
# sql: "SELECT * FROM test FULL OUTER JOIN check ON test.id = check.id"
```

### where
```php
$db->table('users')->where(1)->get();
# sql: "SELECT * FROM users WHERE id = 1"

$db->table('users')->where('status', 1)->get();
# sql: "SELECT * FROM users WHERE status = 1"

$db->table('users')->where('age', '>', 18)->get();
# sql: "SELECT * FROM users WHERE age > 18"

$db->table('users')->where([['id', 1], ['status', 1]])->get();
# sql: "SELECT * FROM users WHERE id = 1 AND status = 1"

$db->table('users')->where([['id', 1], ['status', 1]], 'OR')->get();
# sql: "SELECT * FROM users WHERE id = 1 OR status = 1"
```

### where in
```php
$db->table('test')->whereIn('id', [1, 2, 3])->get();
# sql: "SELECT * FROM test WHERE id IN (1, 2, 3)"

$db->table('test')->whereNotIn('id', [1, 2, 3])->get();
# sql: "SELECT * FROM test WHERE id NOT IN (1, 2, 3)"
```

### between
```php
$db->table('test')->between('age', 17, 25)->get();
# sql: "SELECT * FROM test WHERE age BETWEEN 17, 25"

$db->table('test')->between('age', [17, 25])->get();
# sql: "SELECT * FROM test WHERE age BETWEEN 17, 25"

$db->table('test')->notBetween('age', [17, 25])->get();
# sql: "SELECT * FROM test WHERE age NOT BETWEEN 17, 25"
```

### like
```php
$db->table('test')->like('name', '%example%')->get();
# sql: "SELECT * FROM test WHERE name LIKE %example%"

$db->table('test')->notLike('name', '%example%')->get();
# sql: "SELECT * FROM test WHERE name NOT LIKE %example%"
```

### groupBy
```php
$db->table('test')->groupBy('id')->get();
# sql: "SELECT * FROM test GROUP BY id"

$db->table('test')->groupBy(['id', 'user_id'])->get();
# sql: "SELECT * FROM test GROUP BY id, user_id"
```

### having
```php
$db->table('test')->having('COUNT(user_id)', 5)->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) > 5"

$db->table('test')->having('COUNT(user_id)', '>=', 5)->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) >= 5"

$db->table('test')->having('COUNT(user_id) > ?', [2])->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) >= 5"
```

### orderBy
```php
$db->table('test')->orderBy('name')->get();
# sql: "SELECT * FROM test ORDER BY name ASC"

$db->table('test')->orderBy('name', 'DESC')->get();
# sql: "SELECT * FROM test ORDER BY name DESC"
```

### insert
```php
$data = [
    'username' => 'febrihidayan',
    'status' => 1
];

$db->table('users')->insert($data);
# sql: "INSERT INTO users(username, status) VALUES('febrihidayan', 1)"
```

### update
```php
$data = [
    'username' => 'febrihidayan',
    'status' => 1
];

$db->table('users')->where(1)->update($data);
# sql: "UPDATE users SET username = 'febrihidayan', status = 1 WHERE id = 1"
```

### delete
```php
$db->table('users')->where(1)->delete();
# sql: "DELETE FROM users WHERE id = 1"
```