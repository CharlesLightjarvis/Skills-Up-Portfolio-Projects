<?php

namespace Illuminate\Console\Concerns;

use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Completion\Suggestion;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

trait HasParameters
{





protected function specifyParameters()
{



foreach ($this->getArguments() as $arguments) {
if ($arguments instanceof InputArgument) {
$this->getDefinition()->addArgument($arguments);
} else {
$this->addArgument(...$arguments);
}
}

foreach ($this->getOptions() as $options) {
if ($options instanceof InputOption) {
$this->getDefinition()->addOption($options);
} else {
$this->addOption(...$options);
}
}
}












protected function getArguments()
{
return [];
}













protected function getOptions()
{
return [];
}
}
