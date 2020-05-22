# Dokumentasi Query Builder PHP
Membaca dokumentasi sangat disarankan agar tidak terjadi kesalahan saat menggunakannya.

## Instalasi
Ada beberapa cara menginstall yang bisa di ikuti dibawah ini.

Membuat file `composer.json`
```json
{
    "require": {
        "hilyahtech/query-builder-php": "dev-master"
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
* [where](#where)
* [where in](#where-in)
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
# sql: "SELECT * FROM users"
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
$db->table('users')->whereIn('id', [1, 2, 3])->get();
# sql: "SELECT * FROM users WHERE id IN (1, 2, 3)"
```

### orderBy
```php
$db->table('users')->orderBy('name')->get();
# sql: "SELECT * FROM users ORDER BY name ASC"

$db->table('users')->orderBy('name', 'DESC')->get();
# sql: "SELECT * FROM users ORDER BY name DESC"
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