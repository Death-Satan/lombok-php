<?php


namespace DeathSatan\Lombok\Attributes;


use Attribute;

class BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS,array $extras = []): array
    {
        return [];
    }
}