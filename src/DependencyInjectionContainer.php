<?php
namespace DIM;

use Exception;
use ReflectionMethod;
use ReflectionNamedType;

class DependencyInjectionContainer
{
	protected static $instance;

	/**
	 * Class name with namespace
	 */
	protected string $callbackClass;

	/**
	 * Method name to use
	 */
	protected string $callbackMethod;

	protected string $separator = "@";

	protected string $namespace = __NAMESPACE__ . "\\";

	public static function instance() : DependencyInjectionContainer
	{
		if (is_null(static::$instance))
			self::$instance = new DependencyInjectionContainer();
		return (static::$instance);
	}

	public function call(mixed $callable, array $parameters = [])
	{
		if (is_array($callable))
			$callable = $callable[0] . "@" . $callable[1];
		$this->resolveCallback($callable);
		
		$rm = new ReflectionMethod($this->callbackClass, $this->callbackMethod);
		$methodParams = $rm->getParameters();
		$dependencies = [];

		foreach ($methodParams as $param)
		{
			$type = $param->getType();

			if ($type && $type instanceof ReflectionNamedType && !$type->isBuiltin())
			{
				$instance = (new \ReflectionClass($param->getType()->getName()))->newInstance();
				$dependencies[] = $instance;
			}
			else
			{
				$name = $param->getName();
				if (array_key_exists($name, $parameters))
					$dependencies[] = $parameters[$name];
				else
				{
					if (!$param->isOptional())
						throw new Exception("DIM error: Can not resolve parameters");
				}
			}
		}
		$classInstance = $this->make($this->callbackClass, $parameters);
		return ($rm->invoke($classInstance, ...$dependencies));
	}

	private function resolveCallback(string $callable) : void
	{
		$tmp = explode($this->separator, $callable);
		$this->callbackClass = $this->namespace . $tmp[0];
		$this->callbackMethod = isset($tmp[1]) ? $tmp[1] : '__invoke';
	}

	public function make(string $class, array $parameters = []) : object
	{
		$rc = new \ReflectionClass($class);
		$constructorParams = $rc->getConstructor()->getParameters();
		$dependencies = [];

		foreach ($constructorParams as $param)
		{
			$type = $param->getType();
			if ($type && $type instanceof ReflectionNamedType)
			{
				//not sure about this
				$paramInstance = (new \ReflectionClass($param->getType()->getName()))->newInstance();
				$dependencies[] = $paramInstance;
			}
			else
			{
				$name = $param->getName();
				if (array_key_exists($name, $parameters))
					$dependencies[] = $parameters[$name];
				else
				{
					if (!$param->isOptional())
					{
						throw new \Exception("DIM error: Can not resolve parameters");
					}
				}
			}
		}
		return ($rc->newInstance(...$dependencies));
	}
}
?>