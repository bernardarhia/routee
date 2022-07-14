<?php

namespace Routee\Http;

class Hash
{
    static function encrypt($password, $options = null)
    {
        return password_hash($password, PASSWORD_DEFAULT, $options);
    }

    static function decrypt($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }
    static function rehash($hashedPassword, $options = null)
    {
        return password_needs_rehash($hashedPassword, PASSWORD_DEFAULT, $options);
    }
}