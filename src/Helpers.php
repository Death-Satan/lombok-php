<?php


namespace DeathSatan\Lombok;


class Helpers
{
    public static function checkType($get_type,$reflection_type)
    {
        if ($get_type === 'integer')
        {
            $get_type = 'int';
        }
        return $get_type === $reflection_type;
    }
}