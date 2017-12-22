<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 03/06/2016
 * Time: 10:52
 */

namespace Helpers;


class Basicas
{
    public static function bin2str($input)
    {
        if (!is_string($input)) return null;
        return pack('H*', base_convert($input, 2, 16));
    }
}