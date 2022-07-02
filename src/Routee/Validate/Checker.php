<?php

namespace Routee\Validate;


class Checker
{

    protected function string($value)
    {
        return is_string($value);
    }
    protected function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    protected function phone($value)
    {
        return preg_match('/^\+?[0-9]{10,15}$/', $value);
    }
    protected function integer($value)
    {
        return is_numeric($value);
    }
    protected function boolean($value)
    {
        return is_bool($value);
    }
    protected function float($value)
    {
        return is_float($value);
    }
    protected function array($value)
    {
        return is_array($value);
    }
    protected function object($value)
    {
        return is_object($value);
    }
    protected function null($value)
    {
        return is_null($value);
    }
    protected function date($value)
    {
        $dateSplitted = explode("-", $value);
        if (count($dateSplitted) == 3) {
            return checkdate($dateSplitted[1], $dateSplitted[2], $dateSplitted[0]);
        }
        return false;
    }
    protected function datetime($value)
    {
        return strtotime($value) !== false;
    }
    protected function time($value)
    {
        return strtotime($value) !== false;
    }
    protected function required($value)
    {
        return !empty($value);
    }
    protected function uuid($value)
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $value);
    }
    protected function url($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
    protected function min($value, $min)
    {
        return strlen($value) >= $min;
    }
    protected function max($value, $max)
    {
        return strlen($value) <= $max;
    }
    protected function in($value, $in)
    {
        $data = trim($in, "()");
        $dataSplitted = explode(",", $data);
        return in_array($value, $dataSplitted);
    }
    protected function between($value, $between)
    {
        $between = trim($between, "()");
        $betweenSplitted = explode(",", $between);
        return ($value) >= $betweenSplitted[0] && ($value) <= $betweenSplitted[1];
    }
    protected function alphanumeric($value)
    {
        return preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $value);
    }
    protected function start_with($value, $start_with)
    {
        return strpos($value, $start_with) === 0;
    }
    protected function end_with($value, $end_with)
    {
        return strpos($value, $end_with) === strlen($value) - strlen($end_with);
    }
    protected function file($value)
    {
        return is_file($value);
    }
    protected function ip($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP);
    }
    protected function ipv4($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
    protected function ipv6($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    }
    protected function mac($value)
    {
        return preg_match('/^([0-9a-fA-F]{2}[:-]){5}([0-9a-fA-F]{2})$/', $value);
    }
    protected function same($value, $same)
    {
        return $value == $same;
    }
    protected function gte($value, $gte)
    {
        return $value >= $gte;
    }
    protected function lte($value, $lte)
    {
        return $value <= $lte;
    }
    protected function gt($value, $gt)
    {
        return $value > $gt;
    }
    protected function lt($value, $lt)
    {
        return $value < $lt;
    }
    protected function regex($value, $regex)
    {
        return preg_match($regex, $value);
    }
    protected function eq($value, $eq)
    {
        return $value == $eq;
    }
}