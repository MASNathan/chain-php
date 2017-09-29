<?php

if (!function_exists('with')) {

    function with($value)
    {
        return new \MASNathan\Chain\Item($value);
    }
}
