<?php


namespace DeathSatan\Lombok;


class Helpers
{
    public static function checkType($get_type,$reflection_type):bool
    {
        if ($get_type === '*')
        {
            return true;
        }
        return $get_type === $reflection_type;
    }

    public static function get_type($var):string
    {
        return match (gettype($var)){
            'object'    =>  get_class($var),
            'double'    =>  'float',
            'boolean'   =>  'bool',
            'string'    =>  'string',
            'integer'   =>  'int',
            'NULL'      =>  '*'
        };
    }
}