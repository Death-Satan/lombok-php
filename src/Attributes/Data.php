<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;
use DeathSatan\Lombok\Lombok;
use DeathSatan\Lombok\MethodConstant;
use DeathSatan\StrHelpers\Helpers as Str;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Data extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS,array $extras = []): array
    {
        if ($use_type === Attribute::TARGET_CLASS)
        {
            $__call_closure = function ($name,$params){
                /**
                 * @var Lombok $this
                 */
                $method = substr($name,0,3);
                $property = Str::caramelize(substr($name,3));
                $value = $params[0]??null;
                if (!property_exists($this,$property)){
                    throw new \RuntimeException(sprintf('%s类中不存在%s成员属性',$this::class,$property));
                }
                switch ($method)
                {
                    case 'set':
                        $this->{$property} = $value;
                        return $this;
                    case 'get':
                        return $this->{$property};
                }
            };
            return [
                [
                    MethodConstant::CALL,$__call_closure
                ]
            ];
        }
    }
}