<?php

use DeathSatan\Lombok\Attributes as Lombok;
use DeathSatan\Lombok\Lombok as Helper;
require_once __DIR__.'/../vendor/autoload.php';
#[Lombok\Data]
#[Lombok\RequiredArgsConstructor]
class Demo{
    use Helper;
    public string $user_name;
    public $demo;
    public int $age;
}

$demo = new Demo('123',12);
dump($demo);
