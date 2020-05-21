# Query Builder PHP

Query Builder PDO untuk PHP, dukungan MySQL.

## Instalasi
Cara menginstall Query Builder PHP melalui [composer](http://getcomposer.org), kemudian jalankan perintah berikut untuk instalasi.

```
composer require hilyahtech/query-builder-php
```

## Koneksi
Cara mengoneksi ke database MySQL di PHP cukup mudah dengan melakukan intruksi dibawah ini.

```php
new \HilyahTech\QueryBuilder\DB([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'test',
    'username' => 'root',
    'password' => ''
]);
```

## Penulis
Tim pengembang Query Builder PHP.
- [Febri Hidayan](mailto:febrihidayan20@gmail.com)