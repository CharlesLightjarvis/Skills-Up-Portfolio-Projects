<?php

declare(strict_types=1);

namespace voku\helper;

/**
@psalm-immutable
















*/
final class ASCII
{




const UZBEK_LANGUAGE_CODE = 'uz';

const TURKMEN_LANGUAGE_CODE = 'tk';

const THAI_LANGUAGE_CODE = 'th';

const PASHTO_LANGUAGE_CODE = 'ps';

const ORIYA_LANGUAGE_CODE = 'or';

const MONGOLIAN_LANGUAGE_CODE = 'mn';

const KOREAN_LANGUAGE_CODE = 'ko';

const KIRGHIZ_LANGUAGE_CODE = 'ky';

const ARMENIAN_LANGUAGE_CODE = 'hy';

const BENGALI_LANGUAGE_CODE = 'bn';

const BELARUSIAN_LANGUAGE_CODE = 'be';

const AMHARIC_LANGUAGE_CODE = 'am';

const JAPANESE_LANGUAGE_CODE = 'ja';

const CHINESE_LANGUAGE_CODE = 'zh';

const DUTCH_LANGUAGE_CODE = 'nl';

const ITALIAN_LANGUAGE_CODE = 'it';

const MACEDONIAN_LANGUAGE_CODE = 'mk';

const PORTUGUESE_LANGUAGE_CODE = 'pt';

const GREEKLISH_LANGUAGE_CODE = 'el__greeklish';

const GREEK_LANGUAGE_CODE = 'el';

const HINDI_LANGUAGE_CODE = 'hi';

const SWEDISH_LANGUAGE_CODE = 'sv';

const TURKISH_LANGUAGE_CODE = 'tr';

const BULGARIAN_LANGUAGE_CODE = 'bg';

const HUNGARIAN_LANGUAGE_CODE = 'hu';

const MYANMAR_LANGUAGE_CODE = 'my';

const CROATIAN_LANGUAGE_CODE = 'hr';

const FINNISH_LANGUAGE_CODE = 'fi';

const GEORGIAN_LANGUAGE_CODE = 'ka';

const RUSSIAN_LANGUAGE_CODE = 'ru';

const RUSSIAN_PASSPORT_2013_LANGUAGE_CODE = 'ru__passport_2013';

const RUSSIAN_GOST_2000_B_LANGUAGE_CODE = 'ru__gost_2000_b';

const UKRAINIAN_LANGUAGE_CODE = 'uk';

const KAZAKH_LANGUAGE_CODE = 'kk';

const CZECH_LANGUAGE_CODE = 'cs';

const DANISH_LANGUAGE_CODE = 'da';

const POLISH_LANGUAGE_CODE = 'pl';

const ROMANIAN_LANGUAGE_CODE = 'ro';

const ESPERANTO_LANGUAGE_CODE = 'eo';

const ESTONIAN_LANGUAGE_CODE = 'et';

const LATVIAN_LANGUAGE_CODE = 'lv';

const LITHUANIAN_LANGUAGE_CODE = 'lt';

const NORWEGIAN_LANGUAGE_CODE = 'no';

const VIETNAMESE_LANGUAGE_CODE = 'vi';

const ARABIC_LANGUAGE_CODE = 'ar';

const PERSIAN_LANGUAGE_CODE = 'fa';

const SERBIAN_LANGUAGE_CODE = 'sr';

const SERBIAN_CYRILLIC_LANGUAGE_CODE = 'sr__cyr';

const SERBIAN_LATIN_LANGUAGE_CODE = 'sr__lat';

const AZERBAIJANI_LANGUAGE_CODE = 'az';

const SLOVAK_LANGUAGE_CODE = 'sk';

const FRENCH_LANGUAGE_CODE = 'fr';

const FRENCH_AUSTRIAN_LANGUAGE_CODE = 'fr_at';

const FRENCH_SWITZERLAND_LANGUAGE_CODE = 'fr_ch';

const GERMAN_LANGUAGE_CODE = 'de';

const GERMAN_AUSTRIAN_LANGUAGE_CODE = 'de_at';

const GERMAN_SWITZERLAND_LANGUAGE_CODE = 'de_ch';

const ENGLISH_LANGUAGE_CODE = 'en';

const EXTRA_LATIN_CHARS_LANGUAGE_CODE = 'latin';

const EXTRA_WHITESPACE_CHARS_LANGUAGE_CODE = ' ';

const EXTRA_MSWORD_CHARS_LANGUAGE_CODE = 'msword';




private static $ASCII_MAPS;




private static $ASCII_MAPS_AND_EXTRAS;




private static $ASCII_EXTRAS;




private static $ORD;






private static $REGEX_ASCII = "[^\x09\x10\x13\x0A\x0D\x20-\x7E]";

private const REGEX_PRINTABLE_ASCII = '[^\x20-\x7E]';








private static $BIDI_UNI_CODE_CONTROLS_TABLE = [

8234 => "\xE2\x80\xAA",

8235 => "\xE2\x80\xAB",

8236 => "\xE2\x80\xAC",

8237 => "\xE2\x80\xAD",

8238 => "\xE2\x80\xAE",

8294 => "\xE2\x81\xA6",

8295 => "\xE2\x81\xA7",

8296 => "\xE2\x81\xA8",

8297 => "\xE2\x81\xA9",
];






private const UNKNOWN_TRANSLITERATION_MARKERS = [
'[?]' => true,
'[?] ' => true,
];

















private const UTF8_MULTIBYTE_SEQUENCE_RX = '/[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE-\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2}/';









public static function getAllLanguages(): array
{

static $LANGUAGES = [];

if ($LANGUAGES !== []) {
return $LANGUAGES;
}

foreach ((new \ReflectionClass(__CLASS__))->getConstants() as $constant => $lang) {
if (\strpos($constant, 'EXTRA') !== false) {
$LANGUAGES[\strtolower($constant)] = $lang;
} else {
$LANGUAGES[\strtolower(\str_replace('_LANGUAGE_CODE', '', $constant))] = $lang;
}
}

return $LANGUAGES;
}

/**
@psalm-pure













*/
public static function charsArray(bool $replace_extra_symbols = false): array
{
if ($replace_extra_symbols) {
self::prepareAsciiAndExtrasMaps();

return self::$ASCII_MAPS_AND_EXTRAS ?? [];
}

self::prepareAsciiMaps();

return self::$ASCII_MAPS ?? [];
}

/**
@psalm-pure












*/
public static function charsArrayWithMultiLanguageValues(bool $replace_extra_symbols = false): array
{
static $CHARS_ARRAY = [];
$cacheKey = '' . $replace_extra_symbols;

if (isset($CHARS_ARRAY[$cacheKey])) {
return $CHARS_ARRAY[$cacheKey];
}


$return = [];
$language_all_chars = self::charsArrayWithSingleLanguageValues(
$replace_extra_symbols,
false
);


foreach ($language_all_chars as $key => &$value) {
$return[$value][] = $key;
}

$CHARS_ARRAY[$cacheKey] = $return;

return $return;
}

/**
@psalm-pure
@phpstan-param




















*/
public static function charsArrayWithOneLanguage(
string $language = self::ENGLISH_LANGUAGE_CODE,
bool $replace_extra_symbols = false,
bool $asOrigReplaceArray = true
): array {
$language = self::get_language($language);


static $CHARS_ARRAY = [];
$cacheKey = '' . $replace_extra_symbols . '-' . $asOrigReplaceArray;


if (isset($CHARS_ARRAY[$cacheKey][$language])) {
return $CHARS_ARRAY[$cacheKey][$language];
}

if ($replace_extra_symbols) {
self::prepareAsciiAndExtrasMaps();

if (isset(self::$ASCII_MAPS_AND_EXTRAS[$language])) {
$tmpArray = self::$ASCII_MAPS_AND_EXTRAS[$language];

if ($asOrigReplaceArray) {
$CHARS_ARRAY[$cacheKey][$language] = [
'orig' => \array_keys($tmpArray),
'replace' => \array_values($tmpArray),
];
} else {
$CHARS_ARRAY[$cacheKey][$language] = $tmpArray;
}
} else {
if ($asOrigReplaceArray) {
$CHARS_ARRAY[$cacheKey][$language] = [
'orig' => [],
'replace' => [],
];
} else {
$CHARS_ARRAY[$cacheKey][$language] = [];
}
}
} else {
self::prepareAsciiMaps();

if (isset(self::$ASCII_MAPS[$language])) {
$tmpArray = self::$ASCII_MAPS[$language];

if ($asOrigReplaceArray) {
$CHARS_ARRAY[$cacheKey][$language] = [
'orig' => \array_keys($tmpArray),
'replace' => \array_values($tmpArray),
];
} else {
$CHARS_ARRAY[$cacheKey][$language] = $tmpArray;
}
} else {
if ($asOrigReplaceArray) {
$CHARS_ARRAY[$cacheKey][$language] = [
'orig' => [],
'replace' => [],
];
} else {
$CHARS_ARRAY[$cacheKey][$language] = [];
}
}
}

return $CHARS_ARRAY[$cacheKey][$language] ?? ['orig' => [], 'replace' => []];
}

/**
@psalm-pure














*/
public static function charsArrayWithSingleLanguageValues(
bool $replace_extra_symbols = false,
bool $asOrigReplaceArray = true
): array {

static $CHARS_ARRAY = [];
$cacheKey = '' . $replace_extra_symbols . '-' . $asOrigReplaceArray;

if (isset($CHARS_ARRAY[$cacheKey])) {
return $CHARS_ARRAY[$cacheKey];
}

if ($replace_extra_symbols) {
self::prepareAsciiAndExtrasMaps();


foreach (self::$ASCII_MAPS_AND_EXTRAS ?? [] as &$map) {
$CHARS_ARRAY[$cacheKey][] = $map;
}
} else {
self::prepareAsciiMaps();


foreach (self::$ASCII_MAPS ?? [] as &$map) {
$CHARS_ARRAY[$cacheKey][] = $map;
}
}

$CHARS_ARRAY[$cacheKey] = \array_merge([], ...$CHARS_ARRAY[$cacheKey]);

if ($asOrigReplaceArray) {
$CHARS_ARRAY[$cacheKey] = [
'orig' => \array_keys($CHARS_ARRAY[$cacheKey]),
'replace' => \array_values($CHARS_ARRAY[$cacheKey]),
];
}

return $CHARS_ARRAY[$cacheKey];
}

/**
@psalm-pure



















*/
public static function clean(
string $str,
bool $normalize_whitespace = true,
bool $keep_non_breaking_space = false,
bool $normalize_msword = true,
bool $remove_invisible_characters = true,
bool $remove_invalid_utf8 = true
): string {



if ($remove_invalid_utf8) {
$regex = '/
              (
                (?: [\x00-\x7F]                           # single-byte sequences   0xxxxxxx
                |   [\xC2-\xDF][\x80-\xBF]                # double-byte sequences   110xxxxx 10xxxxxx
                |   \xE0[\xA0-\xBF][\x80-\xBF]            # triple-byte sequences   excluding overlongs
                |   [\xE1-\xEC\xEE-\xEF][\x80-\xBF]{2}    # triple-byte sequences   excluding surrogates
                |   \xED[\x80-\x9F][\x80-\xBF]            # triple-byte sequences   excluding surrogates
                |   \xF0[\x90-\xBF][\x80-\xBF]{2}         # quadruple-byte sequences excluding overlongs
                |   [\xF1-\xF3][\x80-\xBF]{3}             # quadruple-byte sequences
                |   \xF4[\x80-\x8F][\x80-\xBF]{2}         # quadruple-byte sequences up to U+10FFFF
                ){1,100}                                  # ...one or more times
              )
            | ( [\x80-\xBF] )                             # invalid byte in range 10000000 - 10111111
            | ( [\xC0-\xFF] )                             # invalid byte in range 11000000 - 11111111
            /x';
$str = (string) \preg_replace($regex, '$1', $str);
}

if ($normalize_whitespace) {
$str = self::normalize_whitespace($str, $keep_non_breaking_space);
}

if ($normalize_msword) {
$str = self::normalize_msword($str);
}

if ($remove_invisible_characters) {
$str = self::remove_invisible_characters($str);
}

return $str;
}

/**
@psalm-pure














*/
public static function is_ascii(string $str): bool
{
if ($str === '') {
return true;
}

return !\preg_match('/' . self::$REGEX_ASCII . '/', $str);
}

/**
@psalm-pure













*/
public static function normalize_msword(string $str): string
{
if ($str === '') {
return '';
}

static $MSWORD_CACHE = ['orig' => [], 'replace' => []];

if (empty($MSWORD_CACHE['orig'])) {
self::prepareAsciiMaps();

$map = self::$ASCII_MAPS[self::EXTRA_MSWORD_CHARS_LANGUAGE_CODE] ?? [];

$MSWORD_CACHE = [
'orig' => \array_keys($map),
'replace' => \array_values($map),
];
}

return \str_replace($MSWORD_CACHE['orig'], $MSWORD_CACHE['replace'], $str);
}

/**
@psalm-pure















*/
public static function normalize_whitespace(
string $str,
bool $keepNonBreakingSpace = false,
bool $keepBidiUnicodeControls = false,
bool $normalize_control_characters = false
): string {
if ($str === '') {
return '';
}

static $WHITESPACE_CACHE = [];
$cacheKey = (int) $keepNonBreakingSpace;

if ($normalize_control_characters) {
$str = \str_replace(
[
"\x0d\x0c", 
"\xe2\x80\xa8", 
"\xe2\x80\xa9", 
"\x0c", 
"\x0b", 
],
[
"\n",
"\n",
"\n",
"\n",
"\t",
],
$str
);
}

if (!isset($WHITESPACE_CACHE[$cacheKey])) {
self::prepareAsciiMaps();

$WHITESPACE_CACHE[$cacheKey] = self::$ASCII_MAPS[self::EXTRA_WHITESPACE_CHARS_LANGUAGE_CODE] ?? [];

if ($keepNonBreakingSpace) {
unset($WHITESPACE_CACHE[$cacheKey]["\xc2\xa0"]);
}

$WHITESPACE_CACHE[$cacheKey] = array_keys($WHITESPACE_CACHE[$cacheKey]);
}

if (!$keepBidiUnicodeControls) {
static $BIDI_UNICODE_CONTROLS_CACHE = null;

if ($BIDI_UNICODE_CONTROLS_CACHE === null) {
$BIDI_UNICODE_CONTROLS_CACHE = self::$BIDI_UNI_CODE_CONTROLS_TABLE;
}

$str = \str_replace($BIDI_UNICODE_CONTROLS_CACHE, '', $str);
}

return \str_replace($WHITESPACE_CACHE[$cacheKey], ' ', $str);
}

/**
@psalm-pure













*/
public static function remove_invisible_characters(
string $str,
bool $url_encoded = false,
string $replacement = '',
bool $keep_basic_control_characters = true
): string {

$non_displayables = [];





if ($url_encoded) {
$non_displayables[] = '/%0[0-8bcefBCEF]/'; 
$non_displayables[] = '/%1[0-9a-fA-F]/'; 
}

if ($keep_basic_control_characters) {
$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; 
} else {
$str = self::normalize_whitespace($str, false, false, true);
$non_displayables[] = '/[^\P{C}\s]/u';
}

do {
$str = (string) \preg_replace($non_displayables, $replacement, $str, -1, $count);
} while ($count !== 0);

return $str;
}













public static function to_ascii_remap(string $str1, string $str2): array
{
$charMap = [];
$str1 = self::to_ascii_remap_intern($str1, $charMap);
$str2 = self::to_ascii_remap_intern($str2, $charMap);

return [$str1, $str2];
}

/**
@psalm-pure
@phpstan-param




























*/
public static function to_ascii(
string $str,
string $language = self::ENGLISH_LANGUAGE_CODE,
bool $remove_unsupported_chars = true,
bool $replace_extra_symbols = false,
bool $use_transliterate = false,
bool $replace_single_chars_only = false
): string {
if ($str === '') {
return '';
}


if (
!$replace_extra_symbols
&&
!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)
) {
return $str;
}

$language = self::get_language($language);
/**
@phpstan-var */

if (
!$replace_extra_symbols
&&
\strlen($str) <= 64
) {
$isValidUtf8 = true;
$str = self::to_ascii_replace($str, $language, $replace_extra_symbols, $replace_single_chars_only, $isValidUtf8);

if ($isValidUtf8) {
self::prepareAsciiMaps();
if (!isset(self::$ASCII_MAPS[$language])) {
$use_transliterate = true;
}

if ($use_transliterate) {
$str = self::to_transliterate($str, null, false);
}

if ($remove_unsupported_chars) {
if (!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)) {
return $str;
}

$str = (string) \str_replace(["\r\n", "\n", "\r", "\t"], ' ', $str);
$str = (string) \preg_replace('/' . self::$REGEX_ASCII . '/', '', $str);
}

return $str;
}
}





if (
!$replace_extra_symbols
&&
!\preg_match('/[\x80-\xFF]/', $str)
) {
if ($remove_unsupported_chars) {
$str = (string) \str_replace(["\r\n", "\n", "\r", "\t"], ' ', $str);
$str = (string) \preg_replace('/' . self::$REGEX_ASCII . '/', '', $str);
}

return $str;
}





if (\preg_match('//u', $str) !== 1) {
self::prepareAsciiMaps();

if (!isset(self::$ASCII_MAPS[$language])) {
$use_transliterate = true;
}

if ($use_transliterate) {
$str = self::to_transliterate($str, null, false);
}

if ($remove_unsupported_chars) {
if (!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)) {
return $str;
}

$str = (string) \str_replace(["\r\n", "\n", "\r", "\t"], ' ', $str);
$str = (string) \preg_replace('/' . self::$REGEX_ASCII . '/', '', $str);
}

return $str;
}

self::prepareAsciiMaps();
if (!isset(self::$ASCII_MAPS[$language])) {
$use_transliterate = true;
}




if (
$use_transliterate
&&
!$replace_extra_symbols
&&
!$replace_single_chars_only
&&
$language === self::ENGLISH_LANGUAGE_CODE
) {
$str = self::to_transliterate($str, null, false);
} else {

$str = self::to_ascii_replace($str, $language, $replace_extra_symbols, $replace_single_chars_only);

if ($use_transliterate) {
$str = self::to_transliterate($str, null, false);
}
}

if ($remove_unsupported_chars) {
if (!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)) {
return $str;
}

$str = (string) \str_replace(["\r\n", "\n", "\r", "\t"], ' ', $str);
$str = (string) \preg_replace('/' . self::$REGEX_ASCII . '/', '', $str);
}

return $str;
}

/**
@psalm-pure














*/
public static function to_filename(
string $str,
bool $use_transliterate = true,
string $fallback_char = '-'
): string {
if ($use_transliterate) {
$str = self::to_transliterate($str, $fallback_char);
}

$fallback_char_escaped = \preg_quote($fallback_char, '/');

$str = (string) \preg_replace(
[
'/[^' . $fallback_char_escaped . '.\\-a-zA-Z\d\\s]/', 
'/\s+/u', 
'/[' . $fallback_char_escaped . ']+/u', 
],
[
'',
$fallback_char,
$fallback_char,
],
$str
);

return \trim($str, $fallback_char);
}

/**
@psalm-pure
@phpstan-param





















*/
public static function to_slugify(
string $str,
string $separator = '-',
string $language = self::ENGLISH_LANGUAGE_CODE,
array $replacements = [],
bool $replace_extra_symbols = false,
bool $use_str_to_lower = true,
bool $use_transliterate = false
): string {
if ($str === '') {
return '';
}

foreach ($replacements as $from => $to) {
$str = \str_replace($from, $to, $str);
}

if (
!$replace_extra_symbols
&&
!$use_transliterate
&&
self::get_language($language) === self::ENGLISH_LANGUAGE_CODE
&&
!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)
) {

} else {
$str = self::to_ascii(
$str,
$language,
false,
$replace_extra_symbols,
$use_transliterate
);
}

$str = \str_replace('@', $separator, $str);

if ($use_str_to_lower) {
$str = \strtolower($str);
$str = (string) \preg_replace(
'/[^a-z\\d\\s\\-_' . \preg_quote($separator, '/') . ']/',
'',
$str
);
} else {
$str = (string) \preg_replace(
'/[^a-zA-Z\\d\\s\\-_' . \preg_quote($separator, '/') . ']/',
'',
$str
);
$str = (string) \preg_replace('/\\B([A-Z])/', '-\1', $str);
}

$str = (string) \preg_replace('/^[\'\\s]+|[\'\\s]+$/', '', $str);
$str = (string) \preg_replace('/[\\-_\\s]+/', $separator, $str);

$l = \strlen($separator);
if ($l && \strpos($str, $separator) === 0) {
$str = (string) \substr($str, $l);
}

if (\substr($str, -$l) === $separator) {
$str = (string) \substr($str, 0, \strlen($str) - $l);
}

return $str;
}

/**
@psalm-pure
















*/
public static function to_transliterate(
string $str,
$unknown = '?',
bool $strict = false
): string {
static $UTF8_TO_TRANSLIT = null;

static $TRANSLITERATOR = null;

static $SUPPORT_INTL = null;


static $TRANSLIT_CHAR_CACHE = [];

static $WARM_MAPS = [];

if ($str === '') {
return '';
}



if (
isset($str[63])
&&
!\preg_match('/' . self::REGEX_PRINTABLE_ASCII . '/', $str)
) {
return $str;
}


if (\preg_match('/' . self::$REGEX_ASCII . '/', $str) === 0) {
return $str;
}



$unknownCacheKey = $unknown === null
? "\x00null"
: "\x01" . $unknown;

if ($SUPPORT_INTL === null) {
$SUPPORT_INTL = \extension_loaded('intl');
}

$warmPathAlreadyApplied = false;
if (
$unknown !== '?'
&&
isset($WARM_MAPS[$unknownCacheKey])
&&
\preg_match('//u', $str) === 1
) {
$warmStr = \strtr($str, $WARM_MAPS[$unknownCacheKey]);
if (!\preg_match('/[\x80-\xFF\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $warmStr)) {
return $warmStr;
}

$str = $warmStr;
$warmPathAlreadyApplied = true;
}


if (\preg_match('//u', $str) === 1) {
if (
$unknown === '?'
||
\strpos($str, "\xC2") !== false
||
\strpos($str, "\xE2") !== false
||
\preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $str) === 1
) {
$str_before_clean = $str;
$str = self::normalize_whitespace($str);
$str = self::normalize_msword($str);
$str = self::remove_invisible_characters($str);
$str = self::clean(
$str,
true,
false,
true,
false
);
if (
$str !== $str_before_clean
&&
\preg_match('/' . self::$REGEX_ASCII . '/', $str) === 0
) {
return $str;
}
}
} else {
$str_before_clean = $str;
$str = self::clean($str);
if (
$str !== $str_before_clean
&&
\preg_match('/' . self::$REGEX_ASCII . '/', $str) === 0
) {
return $str;
}
}

if (
$strict
&&
$SUPPORT_INTL === true
) {
if (!isset($TRANSLITERATOR)) {

$TRANSLITERATOR = \transliterator_create('NFKC; [:Nonspacing Mark:] Remove; NFKC; Any-Latin; Latin-ASCII;');
}


$str_tmp = \transliterator_transliterate($TRANSLITERATOR, $str);

if ($str_tmp !== false) {
if (
$str_tmp !== $str
&&
\preg_match('/' . self::$REGEX_ASCII . '/', $str_tmp) === 0
) {
return $str_tmp;
}

$str = $str_tmp;
}
}

if (self::$ORD === null) {
self::$ORD = self::getData('ascii_ord');
}




$ordMap = self::$ORD;


if (
!$warmPathAlreadyApplied
&&
isset($WARM_MAPS[$unknownCacheKey])
) {
$str = \strtr($str, $WARM_MAPS[$unknownCacheKey]);

if (!\preg_match('/[\x80-\xFF]/', $str)) {
return $str;
}
}


if (\preg_match_all(self::UTF8_MULTIBYTE_SEQUENCE_RX, $str, $nonAsciiMatches)) {
$charMap = [];
$seen = [];

foreach ($nonAsciiMatches[0] as $c) {
if (isset($seen[$c])) {
continue;
}
$seen[$c] = true;

if (!\array_key_exists($c, $TRANSLIT_CHAR_CACHE)) {
$ordC0 = $ordMap[$c[0]];
$ordC1 = $ordMap[$c[1]];

if ($ordC0 <= 223) {
$ord = ($ordC0 - 192) * 64 + ($ordC1 - 128);
} elseif ($ordC0 <= 239) {
$ord = ($ordC0 - 224) * 4096 + ($ordC1 - 128) * 64 + ($ordMap[$c[2]] - 128);
} else {
$ord = ($ordC0 - 240) * 262144 + ($ordC1 - 128) * 4096 + ($ordMap[$c[2]] - 128) * 64 + ($ordMap[$c[3]] - 128);
}

$bank = $ord >> 8;
if (!isset($UTF8_TO_TRANSLIT[$bank])) {
$UTF8_TO_TRANSLIT[$bank] = self::getDataIfExists(\sprintf('x%03x', $bank));
}

$bankPos = $ord & 255;

if (
isset($UTF8_TO_TRANSLIT[$bank][$bankPos])
&&
!isset(self::UNKNOWN_TRANSLITERATION_MARKERS[$UTF8_TO_TRANSLIT[$bank][$bankPos]])
) {
$TRANSLIT_CHAR_CACHE[$c] = $UTF8_TO_TRANSLIT[$bank][$bankPos];
} else {
$TRANSLIT_CHAR_CACHE[$c] = false;
}
}

$cached = $TRANSLIT_CHAR_CACHE[$c];

if ($cached === false) {
if ($unknown !== null) {
$charMap[$c] = $unknown;
}
} elseif ($cached === '' && $unknown === null) {

} else {
$charMap[$c] = $cached;
}
}


if ($charMap !== []) {
if (isset($WARM_MAPS[$unknownCacheKey])) {
foreach ($charMap as $k => $v) {
$WARM_MAPS[$unknownCacheKey][$k] = $v;
}
} else {
$WARM_MAPS[$unknownCacheKey] = $charMap;
}

return \strtr($str, $WARM_MAPS[$unknownCacheKey]);
}
}

return $str;
}

/**
@phpstan-param




















*/
private static function to_ascii_remap_intern(string $str, array &$map): string
{

$matches = [];
if (!\preg_match_all('/[\xC0-\xF7][\x80-\xBF]+/', $str, $matches)) {
return $str; 
}


$mapCount = \count($map);
foreach ($matches[0] as $mbc) {
if (!isset($map[$mbc])) {
$map[$mbc] = \chr(128 + $mapCount);
++$mapCount;
}
}


return \strtr($str, $map);
}


/**
@phpstan-param
@param-out






*/
private static function to_ascii_replace(
string $str,
string $language,
bool $replace_extra_symbols,
bool $replace_single_chars_only,
?bool &$isValidUtf8 = null
): string {
static $REPLACE_HELPER_CACHE = [];
static $MAP_BY_FIRST_BYTE = [];
static $SHORT_FILTERED_MAP_CACHE = [];
static $SHORT_FILTERED_MAP_CACHE_QUEUE = [];
$cacheKey = $language . '-' . (int) $replace_extra_symbols . '-' . (int) $replace_single_chars_only;

if (!isset($REPLACE_HELPER_CACHE[$cacheKey])) {
$langAll = self::getAsciiAllReplacementMap($replace_extra_symbols, $replace_single_chars_only);

$langSpecific = self::getAsciiLanguageReplacementMap($language, $replace_extra_symbols, $replace_single_chars_only);

if ($langSpecific === []) {
$REPLACE_HELPER_CACHE[$cacheKey] = $langAll;
} else {
$REPLACE_HELPER_CACHE[$cacheKey] = \array_merge([], $langAll, $langSpecific);
}



$MAP_BY_FIRST_BYTE[$cacheKey] = [];
foreach ($REPLACE_HELPER_CACHE[$cacheKey] as $key => $val) {
$MAP_BY_FIRST_BYTE[$cacheKey][$key[0]][$key] = $val;
}
}

if (
!$replace_extra_symbols
&&
\strlen($str) <= 64
) {
$matchResult = \preg_match_all('/' . self::REGEX_PRINTABLE_ASCII . '/u', $str, $matches);
if ($matchResult === false) {
$isValidUtf8 = false;

return $str;
}

$isValidUtf8 = true;

if (!$matchResult) {
return $str;
}

$cache = $REPLACE_HELPER_CACHE[$cacheKey];
$chars = $matches[0];
$charCount = \count($chars);

if ($charCount === 1 && isset($cache[$chars[0]])) {
return \str_replace($chars[0], $cache[$chars[0]], $str);
}

$shortCacheKey = $cacheKey . ':' . \implode('|', $chars);

if (isset($SHORT_FILTERED_MAP_CACHE[$shortCacheKey])) {
return \strtr($str, $SHORT_FILTERED_MAP_CACHE[$shortCacheKey]);
}

$filteredMap = [];

if (
!$replace_single_chars_only
&&
$charCount >= 2
) {


if (\preg_match('/[A-Za-z][\x{0300}-\x{036F}]/u', $str) === 1) {
return \strtr($str, $cache);
}

for ($span = 5; $span >= 2; --$span) {
if ($charCount < $span) {
continue;
}

$lastIndex = $charCount - $span;
for ($idx = 0; $idx <= $lastIndex; ++$idx) {
$candidate = '';
for ($offset = 0; $offset < $span; ++$offset) {
$candidate .= $chars[$idx + $offset];
}

if (isset($cache[$candidate])) {
$filteredMap[$candidate] = $cache[$candidate];
}
}
}
}

foreach ($chars as $char) {
if (isset($cache[$char])) {
$filteredMap[$char] = $cache[$char];
}
}

if ($filteredMap !== []) {
$SHORT_FILTERED_MAP_CACHE[$shortCacheKey] = $filteredMap;
$SHORT_FILTERED_MAP_CACHE_QUEUE[] = $shortCacheKey;
if (\count($SHORT_FILTERED_MAP_CACHE_QUEUE) > 256) {
$oldestKey = \array_shift($SHORT_FILTERED_MAP_CACHE_QUEUE);
if ($oldestKey !== null) {
unset($SHORT_FILTERED_MAP_CACHE[$oldestKey]);
}
}

return \strtr($str, $filteredMap);
}

return $str;
}

$isValidUtf8 = true;



$indexedMap = &$MAP_BY_FIRST_BYTE[$cacheKey];
$filteredMap = [];
foreach (\count_chars($str, 1) as $byte => $count) {
$fb = \chr($byte);
if (isset($indexedMap[$fb])) {
foreach ($indexedMap[$fb] as $k => $v) {
$filteredMap[$k] = $v;
}
}
}

if ($filteredMap !== []) {
$str = \strtr($str, $filteredMap);
}

return $str;
}











private static function get_language(string $language)
{
if ($language === '') {
return '';
}

static $LANGUAGE_CACHE = [];
if (isset($LANGUAGE_CACHE[$language])) {
return $LANGUAGE_CACHE[$language];
}

if (
\strpos($language, '_') === false
&&
\strpos($language, '-') === false
) {
return $LANGUAGE_CACHE[$language] = \strtolower($language);
}

$language_tmp = \str_replace('-', '_', \strtolower($language));

$regex = '/(?<first>[a-z]+)_\g{first}/';

return $LANGUAGE_CACHE[$language] = (string) \preg_replace($regex, '$1', $language_tmp);
}




private static function getAsciiAllReplacementMap(
bool $replace_extra_symbols,
bool $replace_single_chars_only
): array {
static $CACHE = [];
$cacheKey = (int) $replace_extra_symbols . '-' . (int) $replace_single_chars_only;

if (isset($CACHE[$cacheKey])) {
return $CACHE[$cacheKey];
}

$CACHE[$cacheKey] = self::filterAsciiReplacementMap(
self::charsArrayWithSingleLanguageValues($replace_extra_symbols, false),
$replace_single_chars_only
);

return $CACHE[$cacheKey];
}

/**
@phpstan-param


*/
private static function getAsciiLanguageReplacementMap(
string $language,
bool $replace_extra_symbols,
bool $replace_single_chars_only
): array {
static $CACHE = [];
$cacheKey = $language . '-' . (int) $replace_extra_symbols . '-' . (int) $replace_single_chars_only;

if (isset($CACHE[$cacheKey])) {
return $CACHE[$cacheKey];
}

$CACHE[$cacheKey] = self::filterAsciiReplacementMap(
self::charsArrayWithOneLanguage($language, $replace_extra_symbols, false),
$replace_single_chars_only
);

return $CACHE[$cacheKey];
}






private static function getData(string $file)
{
return include __DIR__ . '/data/' . $file . '.php';
}






private static function getDataIfExists(string $file): array
{
$file = __DIR__ . '/data/' . $file . '.php';
if (\is_file($file)) {
return include $file;
}

return [];
}






private static function filterAsciiReplacementMap(array $map, bool $replace_single_chars_only): array
{
if ($replace_single_chars_only === false) {
return $map;
}

foreach ($map as $char => $replacement) {


if (
isset($char[4])
||
\preg_match('/^.$/us', $char) !== 1
) {
unset($map[$char]);
}
}

return $map;
}




private static function prepareAsciiAndExtrasMaps()
{
if (self::$ASCII_MAPS_AND_EXTRAS === null) {
self::prepareAsciiMaps();
self::prepareAsciiExtras();

self::$ASCII_MAPS_AND_EXTRAS = \array_merge_recursive(
self::$ASCII_MAPS ?? [],
self::$ASCII_EXTRAS ?? []
);
}
}




private static function prepareAsciiMaps()
{
if (self::$ASCII_MAPS === null) {
self::$ASCII_MAPS = self::getData('ascii_by_languages');
}
}




private static function prepareAsciiExtras()
{
if (self::$ASCII_EXTRAS === null) {
self::$ASCII_EXTRAS = self::getData('ascii_extras_by_languages');
}
}
}
