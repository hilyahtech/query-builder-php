<?php

namespace HilyahTech\QueryBuilder;

trait Helpers
{
    private $prefix;

    private function isText($val)
    {
        return is_int($val) ? $val : "'{$val}'";
    }

    private function isIm($field)
    {
        return is_array($field) ? implode(', ', $field) : $field;
    }

    private function isTable($field)
    {
        if (is_array($field)) {
            $string = '';

            foreach ($field as $key => $value) {
                $string .= $this->prefix . "{$value}, ";
            }

            return substr($string, 0, -2);
        }
        return $this->prefix . $field;
    }

    private function isSelect($sql, $field, $name)
    {
        return "{$sql}({$field})" . (!is_null($name) ? " AS {$name}" : '');
    }

}