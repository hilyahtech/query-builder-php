<?php

namespace HilyahTech\QueryBuilder;

use PDO;

class DB {

    private $conn;
    private $table;
    
    public function __construct(Array $config)
    {
        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $host = isset($config['host']) ? $config['host'] : 'localhost';
        
        $dns = $driver . ":host=" . $host . ";dbname=" . $config['database'] . ";";

        try {
            $this->conn = new PDO($dns, $config['username'], $config['password']);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function table($table)
    {
        $this->table = is_array($table) ? implode(',', $table) : $table;
        return $this;
    }

    public function get()
    {
        $sql = sprintf("SELECT * FROM %s", $this->table);
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result->fetchAll();
    }

}