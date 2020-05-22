<?php

namespace HilyahTech\QueryBuilder;

use PDO;

class DB {
    use Helpers;

    private $conn;
    private $table;
    private $select = '*';
    private $where;
    private $orderBy;
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
            $where = " id = {$column} ";
        } elseif (empty($value)) {
            $where = " {$column} = " . $this->isText($op);
        } else {
            $where = " {$column} {$op} " . $this->isText($value);
        }

        return $where;
    }

    public function where($column, $op = null, $value = null)
    {
        $_where = '';

        if (is_array($column)) {
            $op = is_null($op) ? 'AND' : $op;

            foreach ($column as $keys => $value) {

                $_where .= $this->setWhere(
                    $value[0],
                    is_int($value[1]) ? $value[1] : (in_array($value[1], $this->op) ? $value[1] : "'{$value[1]}'"),
                    isset($value[2]) ? "'{$value[2]}'" : ''
                ) . $op; 

            }
            
            $_where = substr($_where, 0, -strlen($op));
        } else {
            $_where .= $this->setWhere($column, $op, $value);
        }

        $this->where .= empty($this->where) ? $_where : ' AND ' . $_where;
        
        return $this;
    }

    public function whereIn($column, Array $value)
    {
        $value = implode(', ', $value);
        $_where = "{$column} IN ({$value})";
        $this->where .= empty($this->where) ? $_where : ' AND ' . $_where;
        return $this;
    }

    public function whereNull($column)
    {
        $_where = "{$column} IS NULL";
        $this->where .= empty($this->where) ? $_where : ' AND ' . $_where;
        return $this;
    }

    public function whereNotNull($column)
    {
        $_where .= "{$column} IS NOT NULL";
        $this->where .= empty($this->where) ? $_where : ' AND ' . $_where;
        return $this;
    }

    public function orderBy($column, $sort = 'ASC')
    {
        $this->orderBy = " ORDER BY {$column} {$sort}";
        return $this;
    }

    private function setExtract()
    {
        $sql = '';

        $sql .= !empty($this->where) ? 'WHERE ' . $this->where : '';
        $sql .= $this->orderBy;

        $this->table = null;
        $this->select = null;
        $this->where = null;
        $this->orderBy = null;

        return $sql;
    }
    
    public function get()
    {
        $sql = sprintf("SELECT %s FROM %s %s", $this->select, $this->table, $this->setExtract());
        $result = $this->conn->prepare($sql);
        $result->execute();
        return $result->fetchAll();
    }

    public function first()
    {
        $sql = sprintf("SELECT %s FROM %s %s LIMIT 1", $this->select, $this->table, $this->setExtract());
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

    public function update(Array $fields)
    {
        $_fields = '';

        foreach ($fields as $key => $value) {
            $_fields .= "{$key} = " . $this->istext($value) . ", ";
        }

        $_fields = substr($_fields, 0, -2);

        $sql = sprintf("UPDATE %s SET %s WHERE %s", $this->table, $_fields, $this->where);
        return $this->runQuery($sql);
    }

    public function delete()
    {
        $sql = sprintf("DELETE FROM %s WHERE %s", $this->table, $this->where);
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