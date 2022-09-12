<?php


namespace DeathSatan\Lombok;


use Closure;
use DeathSatan\Lombok\Attributes\BaseAttributes;
use DeathSatan\Lombok\Attributes\NonNull;

trait Lombok
{
    public function __construct()
    {
        // 获取到传入的值
        $this->__handle_lombok__();
        $this->__handle_call__(__FUNCTION__,func_get_args());
        $this->__handle_call__(NonNull::NAME,[]);
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
                static::class,$params[0]??''
            ]));
        }
        return $result;
    }

    // lombok内置处理数组
    protected array $lombok_call = [];

    protected function __handle_closure__($closure_data)
    {
        foreach ($closure_data as $i => $closure_datum)
        {
            $this->lombok_call[$closure_datum[0]][] = $closure_datum[1];
        }
    }

    /**
     * 注解处理
     */
    protected function __handle_attributes__()
    {
        // 类注解处理
        $reflection = new \ReflectionClass(static::class);
        $class_attributes = $reflection->getAttributes();
        foreach ($class_attributes as $class_attribute)
        {
            $class_attribute_object = $class_attribute->newInstance();
            if ($class_attribute_object instanceof BaseAttributes)
            {
                $call_closure_data = $class_attribute_object->handle(\Attribute::TARGET_CLASS);
                $this->__handle_closure__($call_closure_data);
            }
        }
        // 属性注解处理
        $properties_reflections = $reflection->getProperties();
        foreach ($properties_reflections as $properties_reflection)
        {
            $properties_name = $properties_reflection->getName();
            $properties_attributes = $properties_reflection->getAttributes();
            foreach ($properties_attributes as $properties_attribute)
            {
                $properties_attribute_object = $properties_attribute->newInstance();
                if ($properties_attribute_object instanceof BaseAttributes)
                {
                    $call_closure_data = $properties_attribute_object->handle(\Attribute::TARGET_PROPERTY,[
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