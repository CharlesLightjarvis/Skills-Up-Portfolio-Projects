<?php

declare(strict_types=1);

namespace Brick\Math\Exception;




final class RoundingNecessaryException extends MathException
{
/**
@pure
*/
public static function roundingNecessary(): RoundingNecessaryException
{
return new self('Rounding is necessary to represent the result of the operation at this scale.');
}
}
