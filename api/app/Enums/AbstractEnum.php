<?php


namespace App\Enums;

use ReflectionClass;

class AbstractEnum
{

    public static function getValues(): array
    {
        $oClass = new ReflectionClass(static::class);

        return $oClass->getConstants();
    }
}
