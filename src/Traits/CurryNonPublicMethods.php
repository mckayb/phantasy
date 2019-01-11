<?php declare(strict_types=1);

namespace Phantasy\Traits;

use function Phantasy\Core\curryN;

trait CurryNonPublicMethods
{
    public function __get(string $method)
    {
        if (property_exists($this, $method)) {
            return $this->$method;
        } elseif (method_exists($this, $method)) {
            $definingCurriedMethods = isset($this->methodsToCurry) && is_array($this->methodsToCurry);
            $methodIsToBeCurried = $definingCurriedMethods
                ? in_array($method, $this->methodsToCurry)
                : true;
            if ($methodIsToBeCurried) {
                $ref = new \ReflectionMethod($this, $method);
                $numArgs = $ref->getNumberOfRequiredParameters();
                $returnType = (string)$ref->getReturnType();
                $self = $this;
                $func = curryN($numArgs, function (...$args2) use ($self, $method, $returnType) {
                    return call_user_func_array([$self, $method], $args2);
                });
                return $func;
            }
        }
    }

    public function __call(string $method, array $args)
    {
        $methodExists = method_exists($this, $method);
        $definingCurriedMethods = isset($this->methodsToCurry) && is_array($this->methodsToCurry);
        $methodIsToBeCurried = $methodExists &&
            ($definingCurriedMethods ? in_array($method, $this->methodsToCurry) : true);
            
        if ($methodIsToBeCurried) {
            $ref = new \ReflectionMethod($this, $method);
            $numArgs = $ref->getNumberOfRequiredParameters();
            $returnType = (string)$ref->getReturnType();
            $self = $this;
            $func = curryN($numArgs, function (...$args2) use ($self, $method, $returnType) {
                return call_user_func_array([$self, $method], $args2);
            });
            return $func(...$args);
        }
    }

    public static function __callStatic(string $method, array $args)
    {
        $calledClass = get_called_class();
        $refClass = new \ReflectionClass($calledClass);
        $ref = null;
        $methodsToCurry = null;
        $parent = false;
        try {
            $parent = $refClass->getParentClass();
            $refClass = $parent === false ? $refClass : $parent;
            $ref = $refClass->getMethod($method);
            $props = $refClass->getDefaultProperties();
            $methodsToCurry = $props['methodsToCurry'] ?? null;
        } catch (\Exception $e) {
        }
        $methodExists = !is_null($ref);
        $definingCurriedMethods = is_array($methodsToCurry);
        $methodIsToBeCurried = $methodExists &&
            ($definingCurriedMethods ? in_array($method, $methodsToCurry) : true);

        if ($methodIsToBeCurried) {
            $numArgs = $ref->getNumberOfRequiredParameters();
            $returnType = (string)$ref->getReturnType();
            $func = curryN($numArgs, function (...$args2) use ($refClass, $method, $returnType) {
                return call_user_func_array([$refClass->getName(), $method], $args2);
            });
            return $func(...$args);
        }
    }
}
