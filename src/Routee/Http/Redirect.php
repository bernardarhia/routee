<?php

namespace Routee\Http;

class Redirect
{
    public function to($url)
    {
        header("Location: $url");
        exit;
    }

    public function back()
    {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}