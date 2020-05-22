<?php

namespace HilyahTech\QueryBuilder;

use PDO;

class DB {

    private $conn;
    private $table;
    private $select = '*';
    private $where;
    private $op = ['like', '=', '!=', '<', '>', '<=', '>=', '<>'];
    
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

    public function min($field, $name = null)
    {
        $this->select = "MIN({$field})" . (!is_null($name) ? " AS {$name}" : '');
        return $this;
    }

    public function max($field, $name = null)
    {
        $this->select = "MAX({$field})" . (!is_null($name) ? " AS {$name}" : '');
        return $this;
    }

    public function count($field, $name = null)
    {
        $this->select = "COUNT({$field})" . (!is_null($name) ? " AS {$name}" : '');
        return $this;
    }

    public function avg($field, $name = null)
    {
        $this->select = "AVG({$field})" . (!is_null($name) ? " AS {$name}" : '');
        return $this;
    }

    public function sum($field, $name = null)
    {
        $this->select = "SUM({$field})" . (!is_null($name) ? " AS {$name}" : '');
        return $this;
    }

    public function select($fields)
    {
        $this->select = is_array($fields) ? implode(',', $fields) : $fields;
        return $this;
    }

    private function setWhere($column, $op = null, $value = null)
    {
        if (empty($op) && empty($value)) {
            $where = "id = {$column}";
        } elseif (empty($value)) {
            $where = "{$column} = {$op}";
        } else {
            $where = "{$column} {$op} {$value}";
        }

        return $where;
    }

    public function where($column, $op = null, $value = null)
    {
        $_where = 'WHERE ';

        if (is_array($column)) {
            foreach ($column as $keys => $value) {
                $_where .= $this->setWhere(
                    $value[0],
                    is_int($value[1]) ? $value[1] : (in_array($value[1], $this->op) ? $value[1] : "'{$value[1]}'"),
                    isset($value[2]) ? "'{$value[2]}'" : ''
                ) . " AND ";
            }
            
            $_where = substr($_where, 0, -5);
        } else {
            $_where .= $this->setWhere($column, $op, $value);
        }

        $this->where = $_where;
        
        return $this;
    }

    public function orderBy($column, $sort = 'ASC')
    {
        $this->where = " ORDER BY {$column} {$sort}";
        return $this;
    }

    public function get()
    {
        $sql = sprintf("SELECT %s FROM %s %s", $this->select, $this->table, $this->where);
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result->fetchAll();
    }

    public function insert(Array $fields)
    {
        $columns = implode(',', array_keys($fields));
        $values = '';

        foreach ($fields as $key => $val) {
            $values .= is_int($val) ? $val : "'{$val}'" . ",";
        }
        
        $sql = sprintf("INSERT INTO %s (%s) VALUES(%s)", $this->table, $columns, substr($values, 0, -1));
        return $this->runQuery($sql);
    }

    private function runQuery($sql)
    {
        $query = $this->conn->prepare($sql);
        if ($query->execute()) return true;
            else return false;
    }

    public function __destruct()
    {
        if (is_resource($this->conn)) $this->conn = null;
    }

}