<?php

declare(strict_types=1);










namespace Carbon\Constants;

interface DiffOptions
{



public const NO_ZERO_DIFF = 01;
public const JUST_NOW = 02;
public const ONE_DAY_WORDS = 04;
public const TWO_DAY_WORDS = 010;
public const SEQUENTIAL_PARTS_ONLY = 020;
public const ROUND = 040;
public const FLOOR = 0100;
public const CEIL = 0200;




public const DIFF_ABSOLUTE = 1; 
public const DIFF_RELATIVE_AUTO = 0; 
public const DIFF_RELATIVE_TO_NOW = 2;
public const DIFF_RELATIVE_TO_OTHER = 3;
}
