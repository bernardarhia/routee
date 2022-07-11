<?php

namespace Routee\Helpers;

trait Helpers
{
    /**
     * @param array|string $data
     * 
     * turnToJSON is a method that turns an array into a json object
     * 
     * @return object|null
     */
    static function turnToJSON(array $data = null): object|null
    {

        $object = json_decode(json_encode($data));
        return $object ?? null;
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
    static public function sizeFilter($bytes)
    {
        $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
        return (round($bytes, 2) . " " . $label[$i]);
    }

    static function renameFiles($extension)
    {
        return date('dmYHis') . uniqid() . "." . $extension;
    }
}