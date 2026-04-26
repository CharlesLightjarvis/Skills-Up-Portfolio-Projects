<?php










namespace Symfony\Component\Console\Interaction;

use Symfony\Component\Console\Attribute\InteractiveAttributeInterface;
use Symfony\Component\Console\Attribute\MapInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;




final class Interaction
{
public function __construct(
private readonly object $owner,
private readonly InteractiveAttributeInterface $attribute,
) {
}




public function interact(InputInterface $input, OutputInterface $output, \Closure $parameterResolver): void
{
if ($this->owner instanceof MapInput) {
$function = $this->attribute->getFunction($this->owner->resolveValue($input));
$function->invoke(...$parameterResolver($function, $input, $output));
$this->owner->setValue($input, $function->getClosureThis());

return;
}

$function = $this->attribute->getFunction($this->owner);
$function->invoke(...$args = $parameterResolver($function, $input, $output));
foreach ($function->getParameters() as $i => $parameter) {
if (\is_object($args[$i]) && $spec = MapInput::tryFrom($parameter)) {
$spec->setValue($input, $args[$i]);
}
}
}
}
