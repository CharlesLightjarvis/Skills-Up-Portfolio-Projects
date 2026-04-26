<?php

declare(strict_types=1);

namespace Brick\Math;





enum RoundingMode
{






case Unnecessary;







case Up;







case Down;







case Ceiling;







case Floor;







case HalfUp;






case HalfDown;






case HalfCeiling;






case HalfFloor;











case HalfEven;




public const UNNECESSARY = self::Unnecessary;




public const UP = self::Up;




public const DOWN = self::Down;




public const CEILING = self::Ceiling;




public const FLOOR = self::Floor;




public const HALF_UP = self::HalfUp;




public const HALF_DOWN = self::HalfDown;




public const HALF_CEILING = self::HalfCeiling;




public const HALF_FLOOR = self::HalfFloor;




public const HALF_EVEN = self::HalfEven;
}
