# Introduction
 DIM stands for DependencyInjectionManager. It has been developped from the followed article https://dev.to/emrancu/let-s-create-php-dependency-injection-container-24lm. 
 
 I did this to train myself with the concept of dependency injection. It will now be used in my "Everate" Framework.

# Usage

## Retrieve a class

To create a class with the DI container you can process like this :

```php
use DIM\DependencyInjectionContainer;

$container = DependencyInjectionContainer::instance();
$class = $container->make("class with namespace here");

$class->method();
```

## Execute a method

Let's assume we have the following class

```php
namespace DIM;

use Dim\Http\Request;
use Dim\Http\Response;

class UserController
{
    public function addToCart(Request $request, Response $response) : Response
    {
        if ($request->isGood())
            return ($response->success());
        return ($response->failed());
    }

    public function test(int $number) : void
    {
        echo "Trying to echo a number throught DI : $number";
    }
}
```
But you dont want to instantiate all manually. Then you can ask the container to automaticly call methods that don't need a values in parameters.

```php
use DIM\DependencyInjectionContainer;

$container = DependencyInjectionContainer::instance();

//First way
$class = $container->call("UserController@addToCart");

//Second way
$class = $container->call(["UserController", "test"], ["number" => 4]);
```