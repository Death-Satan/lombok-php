<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RequiredArgsConstructor extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS, array $extras = []): array
    {
        return [

        ];
    }
}