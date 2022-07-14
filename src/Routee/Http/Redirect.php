<?php

namespace Routee\Http;

class Redirect
{
    static public function to($url)
    {
        header("Location: $url");
        exit;
    }

    static public function back()
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}