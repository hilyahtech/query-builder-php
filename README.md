# Query Builder PHP

Query Builder PDO untuk PHP, dukungan MySQL.

## Instalasi
Cara menginstall Query Builder PHP melalui [composer](http://getcomposer.org), kemudian jalankan perintah berikut untuk instalasi.

```sh
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

## Dokumen
Halaman dokumentasi: [Docs](https://github.com/hilyahtech/query-builder-php/blob/master/DOCS.md)

## Penulis
Tim pengembang Query Builder PHP.
- [Febri Hidayan](mailto:febrihidayan20@gmail.com)