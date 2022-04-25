<?php
namespace DIM\libs;

class Autoloader{

		static function register() : bool
		{
			return (spl_autoload_register(array(__CLASS__, 'autoload')));
		}

		static function autoload(string $class)
		{
			$fileContent = file_get_contents(__DIR__ . "/../../config/autoloader.json");
			$array = json_decode($fileContent, true);

			foreach ($array as $namespace => $path)
			{
				if (strpos($class, $namespace) === 0)
				{
					$class = str_replace($namespace, $path, $class);
					$class = str_replace('\\', '/', $class);
					require (__DIR__ . "/../../$class.php");
					break ;
				}
			}
		}
}

Autoloader::register();