<?php

use DeathSatan\Lombok\Attributes as Lombok;
use DeathSatan\Lombok\Lombok as Helper;
require_once __DIR__.'/../vendor/autoload.php';
#[Lombok\AllArgsConstructor]
class Demo{
    use Helper;
    public string $user_name;
    public ?string $demo;
    public int $age;
}

$demo = new Demo('123',null,123);
