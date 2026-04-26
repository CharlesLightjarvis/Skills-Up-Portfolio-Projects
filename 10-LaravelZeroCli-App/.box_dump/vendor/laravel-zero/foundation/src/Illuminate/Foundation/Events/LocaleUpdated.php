<?php

namespace Illuminate\Foundation\Events;

class LocaleUpdated
{





public $locale;






public $previousLocale;







public function __construct($locale, $previousLocale = null)
{
$this->locale = $locale;

$this->previousLocale = $previousLocale;
}
}
