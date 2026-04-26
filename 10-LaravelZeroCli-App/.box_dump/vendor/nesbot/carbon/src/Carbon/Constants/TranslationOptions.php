<?php

declare(strict_types=1);










namespace Carbon\Constants;

interface TranslationOptions
{



public const TRANSLATE_MONTHS = 1;
public const TRANSLATE_DAYS = 2;
public const TRANSLATE_UNITS = 4;
public const TRANSLATE_MERIDIEM = 8;
public const TRANSLATE_DIFF = 0x10;
public const TRANSLATE_ALL = self::TRANSLATE_MONTHS | self::TRANSLATE_DAYS | self::TRANSLATE_UNITS | self::TRANSLATE_MERIDIEM | self::TRANSLATE_DIFF;




public const WEEK_DAY_AUTO = 'auto';






public const DEFAULT_LOCALE = 'en';
}
