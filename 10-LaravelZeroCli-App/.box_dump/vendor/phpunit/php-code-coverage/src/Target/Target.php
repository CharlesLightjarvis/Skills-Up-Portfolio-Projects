<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\Test\Target;

/**
@immutable
@no-named-arguments

*/
abstract class Target
{



public static function forNamespace(string $namespace): Namespace_
{
return new Namespace_($namespace);
}




public static function forClass(string $className): Class_
{
return new Class_($className);
}





public static function forMethod(string $className, string $methodName): Method
{
return new Method($className, $methodName);
}




public static function forClassesThatImplementInterface(string $interfaceName): ClassesThatImplementInterface
{
return new ClassesThatImplementInterface($interfaceName);
}




public static function forClassesThatExtendClass(string $className): ClassesThatExtendClass
{
return new ClassesThatExtendClass($className);
}




public static function forFunction(string $functionName): Function_
{
return new Function_($functionName);
}




public static function forTrait(string $traitName): Trait_
{
return new Trait_($traitName);
}

public function isNamespace(): bool
{
return false;
}

public function isClass(): bool
{
return false;
}

public function isMethod(): bool
{
return false;
}

public function isClassesThatImplementInterface(): bool
{
return false;
}

public function isClassesThatExtendClass(): bool
{
return false;
}

public function isFunction(): bool
{
return false;
}

public function isTrait(): bool
{
return false;
}




abstract public function key(): string;




abstract public function target(): string;




abstract public function description(): string;
}
