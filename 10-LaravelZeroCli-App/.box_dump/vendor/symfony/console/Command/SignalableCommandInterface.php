<?php










namespace Symfony\Component\Console\Command;






interface SignalableCommandInterface
{
/**
@@return list<\SIG*>




*/
public function getSubscribedSignals(): array;






public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false;
}
