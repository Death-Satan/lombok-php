<?php


namespace DeathSatan\Lombok;


use Attribute;
use Closure;
use DeathSatan\Lombok\Attributes\BaseAttributes;
use DeathSatan\Lombok\Attributes\NonNull;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

trait Lombok
{
    public function __construct()
    {
        // 获取到传入的值
        $this->__handle_lombok__();
        $this->__handle_call__(__FUNCTION__,func_get_args());
        $this->__handle_call__(MethodConstant::NONNULL,[]);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->__handle_call__(__FUNCTION__,[$name,$arguments]);
    }

    protected function __handle_call__(string $name,array $params)
    {
        if (empty($this->lombok_call[$name]))
        {
            return;
        }
        /**
         * @var Closure[] $call_closures
         */
        $call_closures = $this->lombok_call[$name];
        $result = null;
        foreach ($call_closures as $closure)
        {
            $closure = $closure->bindTo($this,static::class);
            if ($result===null) {
                $result = $closure(...$params);
            }else{
                $result = $closure(...$result);
            }
            if (!is_array($result) && $result!==null)
            {
                break;
            }
        }
        if ($result===$params)
        {
            throw new \RuntimeException(sprintf('类%s中不能有效处理%s方法',...[
                static::class,$params[0]??MethodConstant::CONSTRUCT
            ]));
        }
        return $result;
    }

    // lombok内置处理数组
    protected array $lombok_call = [];


    /**
     * @var ReflectionAttribute[] $reflection_class_attributes
     */
    protected array $reflection_class_attributes = [];

    /**
     * @var ReflectionAttribute[] $reflection_properties_attributes
     */
    protected array $reflection_properties_attributes = [];


    protected ReflectionClass $reflection_class;

    /**
     * @var ReflectionProperty[] $reflection_properties
     */
    protected array $reflection_properties;

    protected function __handle_closure__($closure_data)
    {
        foreach ($closure_data as $i => $closure_datum)
        {
            $this->lombok_call[$closure_datum[0]][] = $closure_datum[1];
        }
    }

    /**
     * 不需要处理的数据
     * @return string[]
     */
    protected function getNotHandleProperties():array
    {
        return [
            'reflection_class',
            'reflection_properties',
            'reflection_properties_attributes',
            'reflection_class_attributes',
            'lombok_call'
        ];
    }

    /**
     * 注解处理
     */
    protected function __handle_attributes__()
    {
        // 类注解处理
        $this->reflection_class = $reflection = new ReflectionClass(static::class);
        $class_attributes = $reflection->getAttributes();
        foreach ($class_attributes as $class_attribute)
        {
            $class_attribute_object = $class_attribute->newInstance();

            // 将类注解反射存储一下
            $this->reflection_class_attributes = array_merge(
             $this->reflection_class_attributes,
             [$class_attribute_object]
            );

            if ($class_attribute_object instanceof BaseAttributes)
            {
                $call_closure_data = $class_attribute_object->handle(Attribute::TARGET_CLASS);
                $this->__handle_closure__($call_closure_data);
            }
        }
        // 属性注解处理
        $properties_reflections = $reflection->getProperties();
        foreach ($properties_reflections as $properties_reflection)
        {
            $properties_name = $properties_reflection->getName();
            // 不需要处理的数据直接抛出去
            if (in_array($properties_name,$this->getNotHandleProperties()))
            {
                continue;
            }
            $this->reflection_properties[] = $properties_reflection;
            $properties_attributes = $properties_reflection->getAttributes();
            foreach ($properties_attributes as $properties_attribute)
            {
                $properties_attribute_object = $properties_attribute->newInstance();
                // 将属性注解反射进行存储一下
                $this->reflection_properties_attributes[$properties_name][] =
                    $properties_attribute_object;

                if ($properties_attribute_object instanceof BaseAttributes)
                {
                    $call_closure_data = $properties_attribute_object->handle(Attribute::TARGET_PROPERTY,[
                        'property_name'=>$properties_name
                    ]);
                    $this->__handle_closure__($call_closure_data);
                }
            }
        }
    }

    /**
     * lombok处理
     */
    protected function __handle_lombok__()
    {
        $this->__handle_attributes__();
    }
}