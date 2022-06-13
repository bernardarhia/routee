<?php

namespace View;

class View
{
    private static $path = null;

    /**
     * 
     * @param string $path
     *@param array $data
     * @return void
     
     */
    public static function render(string $view, array $data = []): void
    {
        if (strpos($view, ".") !== false) {
            $view = str_replace(".", "/", $view);
        }
        $data = json_decode(json_encode($data));
        include static::$path . DIRECTORY_SEPARATOR . $view . ".php";
    }

    /**
     * 
     * @param string $path Gets the path for the views
     */

    static public function getPath(string $path)
    {
        self::$path = $path;
    }
}
