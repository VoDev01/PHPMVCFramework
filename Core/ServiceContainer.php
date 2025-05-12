<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionNamedType;
use InvalidArgumentException;

class ServiceContainer
{
    private array $registry = [];

    public function set(string $name, Closure $value): void
    {
        $this->registry[$name] = $value;
    }

    public function get(string $className): object
    {
        if (array_key_exists($className, $this->registry))
        {
            return $this->registry[$className]();
        }

        $reflector = new ReflectionClass($className);
        $constructor = $reflector->getConstructor();
        $dependecies = [];

        if ($constructor === null)
        {
            return new $className;
        }

        try
        {
            foreach ($constructor->getParameters() as $parameter)
            {
                $type = $parameter->getType();

                if ($type === null)
                {
                    throw new InvalidArgumentException(
                        "Constructor parameter '{$parameter->getName()}' has no type declaration in the $className class",
                    );
                    break;
                }

                if (!($type instanceof ReflectionNamedType))
                {
                    throw new InvalidArgumentException(
                        "Constructor parameter '{$parameter->getName()}' 
                    in the $className class is an invalid type: '$type' - 
                    only single named types are supported",
                    );
                    break;
                }

                if ($type->isBuiltin())
                {
                    throw new InvalidArgumentException(
                        "Unable to resolve constructor parameter '{$parameter->getName()}' of type '$type' in the $className class",
                    );
                    break;
                }

                $dependecies[] = $this->get((string) $type->getName());
            }
        }
        catch (Exception $e)
        {
            print($e->getMessage() . " in {$e->getFile()} on line {$e->getLine()}");
            exit;
        }

        return new $className(...$dependecies);
    }
}
