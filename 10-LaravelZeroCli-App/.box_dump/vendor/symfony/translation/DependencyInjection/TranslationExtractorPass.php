<?php










namespace Symfony\Component\Translation\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;




class TranslationExtractorPass implements CompilerPassInterface
{
public function process(ContainerBuilder $container): void
{
if (!$container->hasDefinition('translation.extractor')) {
return;
}

$definition = $container->getDefinition('translation.extractor');

foreach ($container->findTaggedServiceIds('translation.extractor', true) as $id => $attributes) {
$definition->addMethodCall('addExtractor', [$attributes[0]['alias'] ?? $id, new Reference($id)]);
}
}
}
