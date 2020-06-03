<?php
namespace App\Helper;

class StringHelper
{
    /**
     * @param string $string
     * @return string
     */
    public static function Slugify(string $string): string
    {
        $string = str_replace('+', ' plus', $string);
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
