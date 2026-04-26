<?php










namespace Filter;

if (\PHP_VERSION_ID < 80500) {
class FilterFailedException extends FilterException
{
}
}
