<?php

use DeathSatan\Lombok\Attributes as Lombok;
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
