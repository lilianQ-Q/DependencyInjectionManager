<?php
namespace DIM;

class MyClass
{
    private $dependency;

    public function __construct(AnotherClass $dependency)
    {
        $this->dependency = $dependency;
    }

    public function test(int $name)
    {
        echo "$name\n";
    }
}

?>