<?php


namespace DeathSatan\Lombok\Attributes;

use Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Setter extends BaseAttributes
{
    public function handle(int $use_type = Attribute::TARGET_CLASS, array $extras = []): array
    {
        $property_name = $extras['property_name']??null;
        // 对属性上的处理
        if ($use_type === Attribute::TARGET_PROPERTY)
        {
            $closure = function (...$params)use ($property_name){
                // 当前的方法名
                $property = $params[0]??null;
                // 方法参数
                $property_params = $params[1]??[];
                // 获取方法名前三位
                $method = substr($property,0,3);
                // 获取方法名第三位后面所有，并且大驼峰转下划线
                $property_call_name = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', substr($property,3)));
                if ($method!=='set')
                {
                    return $params;
                }else{
                    if (!property_exists($this,$property_call_name) || $property_call_name!==$property_name)
                    {
                        return $params;
                    }
                    if (count($property_params)!==1){
                        return $params;
                    }
                    $this->{$property_call_name} = $property_params[0];
                    return $this;
                }
            };
            return [
                [
                    '__call',$closure
                ]
            ];
        }
    }
}