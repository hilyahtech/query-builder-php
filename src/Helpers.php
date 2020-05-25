<?php

namespace HilyahTech\QueryBuilder;

trait Helpers {

    private function isText($val)
    {
        return is_int($val) ? $val : "'{$val}'";
    }

    private function isIm($field)
    {
        return is_array($field) ? implode(', ', $field) : $field;
    }

    private function isSelect($sql, $field, $name)
    {
        return "{$sql}({$field})" . (!is_null($name) ? " AS {$name}" : '');
    }

}