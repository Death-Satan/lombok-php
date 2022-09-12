# Implementing Lombok similar to Java with PHP
- 用PHP实现类似Java的Lombok

# 已实现注解

---
- Data (为所有的属性生成对应的Get,Set方法)
- Getter （单独的为一个属性生成Get方法)
- Setter (单独的为一个属性生成Set方法)
- 更多待实现
---

#示例

---
```php
<?php
/** 所有的注解都存放在DeathSatan\Lombok\Attributes空间下 */
use DeathSatan\Lombok\Attributes as Lombok;
// 为注解的类继承Helper Trait方法
use DeathSatan\Lombok\Lombok as Helper;
require_once __DIR__.'/../vendor/autoload.php';
#[Lombok\Data]
class Demo{
    use Helper;
    public int $a;
    public string $b;
    public float $c;
    public array $d;
}

$demo = new Demo();
$demo->setA(124);
$a = $demo->getA();
var_dump($a);
// output 124

```