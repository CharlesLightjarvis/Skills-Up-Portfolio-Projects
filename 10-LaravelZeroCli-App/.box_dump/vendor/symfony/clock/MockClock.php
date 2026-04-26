<?php










namespace Symfony\Component\Clock;








final class MockClock implements ClockInterface
{
private DatePoint $now;





public function __construct(\DateTimeImmutable|string $now = 'now', \DateTimeZone|string|null $timezone = null)
{
if (\is_string($timezone)) {
$timezone = new \DateTimeZone($timezone);
}

if (\is_string($now)) {
$now = new DatePoint($now, $timezone ?? new \DateTimeZone('UTC'));
} elseif (!$now instanceof DatePoint) {
$now = DatePoint::createFromInterface($now);
}

$this->now = null !== $timezone ? $now->setTimezone($timezone) : $now;
}

public function now(): DatePoint
{
return clone $this->now;
}

public function sleep(float|int $seconds): void
{
if (0 >= $seconds) {
return;
}

$now = (float) $this->now->format('Uu') + $seconds * 1e6;
$now = substr_replace(\sprintf('@%07.0F', $now), '.', -6, 0);
$timezone = $this->now->getTimezone();

$this->now = DatePoint::createFromInterface(new \DateTimeImmutable($now, $timezone))->setTimezone($timezone);
}




public function modify(string $modifier): void
{
$this->now = $this->now->modify($modifier);
}




public function withTimeZone(\DateTimeZone|string $timezone): static
{
if (\is_string($timezone)) {
$timezone = new \DateTimeZone($timezone);
}

$clone = clone $this;
$clone->now = $clone->now->setTimezone($timezone);

return $clone;
}
}
