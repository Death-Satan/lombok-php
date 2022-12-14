<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;
use DeathSatan\Lombok\MethodConstant;

#[Attribute(Attribute::TARGET_PROPERTY)]
class NonNull extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_PROPERTY,array $extras = []): array
    {
        $property_name = $extras['property_name'];

        $__construct = function ()use ($extras,$property_name){
            if (
                empty($this->{$property_name}) || $this->{$property_name} === null
            ){
                throw new \RuntimeException(sprintf('%s类中的成员变量%s不能为空',$this::class,$property_name));
            }
        };
        return [
            [
                MethodConstant::NONNULL,$__construct
            ]
        ];
    }
}