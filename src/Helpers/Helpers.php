<?php

namespace Helpers;

trait Helpers
{
    /**
     * @param array $array=[]
     * 
     * turnToJSON is a method that turns an array into a json object
     * 
     * @return object
     */
    static function turnToJSON(array $array = [])
    {
        return json_decode(json_encode($array)) ?? null;
    }
    /**
     * 
     * @param array $array
     * 
     * Arrange arrays into a key value pair 
     * 
     * @return array
     * 
     */
    static function arrangeArray(array $array): array
    {

        $newArray = [];
        foreach ($array as $key => $value) {
            if (!empty($value)) $newArray[] = $value;
        }
        return $newArray;
    }
    /**
     * 
     * @param string $string
     * 
     * Determines whether a string is a json object or not
     * 
     * @return bool
     * 
     */
    static function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}