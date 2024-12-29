<?php

namespace App\Http\Core;

class Singleton{
    private static $objectList = [];

    public static function Create($class){

        if (!isset(self::$objectList[$class])){
            self::$objectList[$class] = new $class();
        }
        
        return self::$objectList[$class];
    }
}