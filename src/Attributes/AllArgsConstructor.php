<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;
use DeathSatan\Lombok\Helpers;
use DeathSatan\Lombok\Lombok;
use DeathSatan\Lombok\MethodConstant;
use RuntimeException;

#[Attribute(Attribute::TARGET_CLASS)]
class AllArgsConstructor extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS, array $extras = []): array
    {
        $closure = function (...$vars){
            /**
             * @var Lombok $this
             */
            $require_vars = [];
            foreach ($this->reflection_properties as $reflection_property)
            {
                $type = $reflection_property->getType()->getName() ?? 'mixed';
                $name = $reflection_property->getName();
                $require_vars[$name] = $type;
            }
            if (count($require_vars)!==count($vars))
            {
                throw new RuntimeException(sprintf(
                    '类%s实例化需要传入%d个参数,目前参数数量为%d',
                    get_class($this),
                    count($require_vars),
                    count($vars)
                ));
            }
            $i=0;
            foreach ($require_vars as $key => $type)
            {
                $get_type =Helpers::get_type($vars[$i]);
                if (!Helpers::checkType($get_type,$type)){
                    throw new RuntimeException(sprintf(
                        '类%s中__construct方法第%d个参数类型要求%s,现在传入的参数类型为%s',
                        get_class($this),
                        $i+1,
                        $get_type,
                        $type
                    ));
                }
                $this->{$key} = $vars[$i];
                $i++;
            }
        };
        return [
            [
                MethodConstant::CONSTRUCT,
                $closure
            ]
        ];
    }
}