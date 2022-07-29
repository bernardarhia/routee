<?php
function csrf_token()
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}

function csrf_field()
{
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}