<?php

namespace Faker\Provider\fr_FR;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{


protected static $formats = [
'+33 (0)1 ## ## ## ##',
'+33 (0)1 ## ## ## ##',
'+33 (0)2 ## ## ## ##',
'+33 (0)3 ## ## ## ##',
'+33 (0)4 ## ## ## ##',
'+33 (0)5 ## ## ## ##',
'+33 (0)6 {{phoneNumber06WithSeparator}}',
'+33 (0)7 {{phoneNumber07WithSeparator}}',
'+33 (0)8 {{phoneNumber08WithSeparator}}',
'+33 (0)9 ## ## ## ##',
'+33 1 ## ## ## ##',
'+33 1 ## ## ## ##',
'+33 2 ## ## ## ##',
'+33 3 ## ## ## ##',
'+33 4 ## ## ## ##',
'+33 5 ## ## ## ##',
'+33 6 {{phoneNumber06WithSeparator}}',
'+33 7 {{phoneNumber07WithSeparator}}',
'+33 8 {{phoneNumber08WithSeparator}}',
'+33 9 ## ## ## ##',
'01########',
'01########',
'02########',
'03########',
'04########',
'05########',
'06{{phoneNumber06}}',
'07{{phoneNumber07}}',
'08{{phoneNumber08}}',
'09########',
'01 ## ## ## ##',
'01 ## ## ## ##',
'02 ## ## ## ##',
'03 ## ## ## ##',
'04 ## ## ## ##',
'05 ## ## ## ##',
'06 {{phoneNumber06WithSeparator}}',
'07 {{phoneNumber07WithSeparator}}',
'08 {{phoneNumber08WithSeparator}}',
'09 ## ## ## ##',
];



protected static $mobileFormats = [
'+33 (0)6 {{phoneNumber06WithSeparator}}',
'+33 6 {{phoneNumber06WithSeparator}}',
'+33 (0)7 {{phoneNumber07WithSeparator}}',
'+33 7 {{phoneNumber07WithSeparator}}',
'06{{phoneNumber06}}',
'07{{phoneNumber07}}',
'06 {{phoneNumber06WithSeparator}}',
'07 {{phoneNumber07WithSeparator}}',
];

protected static $serviceFormats = [
'+33 (0)8 {{phoneNumber08WithSeparator}}',
'+33 8 {{phoneNumber08WithSeparator}}',
'08 {{phoneNumber08WithSeparator}}',
'08{{phoneNumber08}}',
];

protected static $e164Formats = [
'+33#########',
];

public function phoneNumber06()
{
$phoneNumber = $this->phoneNumber06WithSeparator();

return str_replace(' ', '', $phoneNumber);
}







public function phoneNumber06WithSeparator()
{
$regex = '([0-24-8]\d|3[0-8]|9[589])( \d{2}){3}';

return static::regexify($regex);
}

public function phoneNumber07()
{
$phoneNumber = $this->phoneNumber07WithSeparator();

return str_replace(' ', '', $phoneNumber);
}







public function phoneNumber07WithSeparator()
{
$regex = '([3-8]\d)( \d{2}){3}';

return static::regexify($regex);
}

public function phoneNumber08()
{
$phoneNumber = $this->phoneNumber08WithSeparator();

return str_replace(' ', '', $phoneNumber);
}




















public function phoneNumber08WithSeparator()
{
$regex = '([012]\d|(9[1-357-9])( \d{2}){3}';

return static::regexify($regex);
}




public function mobileNumber()
{
$format = static::randomElement(static::$mobileFormats);

return static::numerify($this->generator->parse($format));
}




public function serviceNumber()
{
$format = static::randomElement(static::$serviceFormats);

return static::numerify($this->generator->parse($format));
}
}
