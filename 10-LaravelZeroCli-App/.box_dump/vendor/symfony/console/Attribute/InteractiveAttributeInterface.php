<?php










namespace Symfony\Component\Console\Attribute;




interface InteractiveAttributeInterface
{
public function getFunction(object $instance): \ReflectionFunction;
}
