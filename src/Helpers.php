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

}