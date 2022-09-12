<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;
use DeathSatan\Lombok\Helpers;
use DeathSatan\Lombok\Lombok;
use DeathSatan\Lombok\MethodConstant;
use RuntimeException;

#[Attribute(Attribute::TARGET_CLASS)]
class RequiredArgsConstructor extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS, array $extras = []): array
    {
        $closure = function (...$vars){
            /**
             * @var Lombok $this
             */
            $required_vars = [];
            foreach ($this->reflection_properties as $reflection_property)
            {;
                if (!$reflection_property->hasDefaultValue())
                {
                    $required_vars[$reflection_property->getName()] = $reflection_property->getType()??'mixed';
                }
            }
            if (count($vars)!==count($required_vars))
            {
                return $vars;
            }
            $i = 0;
            foreach ($required_vars as $key => $value)
            {
                $current_value = $vars[$i];
                $get_type =gettype($current_value);
                if ($get_type === 'object')
                {
                    $get_type = get_class($get_type);
                }
                if (!Helpers::checkType($get_type,$value->getName())) {
                    throw new RuntimeException(sprintf(
                        '类%s中第%d参数类型应该为%s,现在传入的类型为%s',
                               get_class($this),
                                $i,
                                $value->getName(),
                                gettype($current_value)
                    ));
                }
                $this->{$key} = $current_value;
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