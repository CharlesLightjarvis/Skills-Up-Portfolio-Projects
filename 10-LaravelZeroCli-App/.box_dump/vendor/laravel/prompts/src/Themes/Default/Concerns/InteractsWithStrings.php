<?php

namespace Laravel\Prompts\Themes\Default\Concerns;

trait InteractsWithStrings
{





protected function longest(array $lines, int $padding = 0): int
{
return max(
$this->minWidth,
count($lines) > 0 ? max(array_map(fn ($line) => mb_strwidth($this->stripEscapeSequences($line)) + $padding, $lines)) : null
);
}




protected function pad(string $text, int $length, string $char = ' '): string
{
$rightPadding = str_repeat($char, max(0, $length - mb_strwidth($this->stripEscapeSequences($text))));

return "{$text}{$rightPadding}";
}




protected function stripEscapeSequences(string $text): string
{

$text = preg_replace("/\e[^m]*m/", '', $text);


$text = preg_replace("/<(info|comment|question|error)>(.*?)<\/\\1>/", '$2', $text);


return preg_replace("/<(?:(?:[fb]g|options)=[a-z,;]+)+>(.*?)<\/>/i", '$1', $text);
}






protected function mbWordwrap(
string $string,
int $width = 75,
string $break = "\n",
bool $cut_long_words = false
): string {
$lines = explode($break, $string);
$result = [];

foreach ($lines as $originalLine) {
if (mb_strwidth($originalLine) <= $width) {
$result[] = $originalLine;

continue;
}

$words = explode(' ', $originalLine);
$line = null;
$lineWidth = 0;

if ($cut_long_words) {
foreach ($words as $index => $word) {
$characters = mb_str_split($word);
$strings = [];
$str = '';

foreach ($characters as $character) {
$tmp = $str.$character;

if (mb_strwidth($tmp) > $width) {
$strings[] = $str;
$str = $character;
} else {
$str = $tmp;
}
}

if ($str !== '') {
$strings[] = $str;
}

$words[$index] = implode(' ', $strings);
}

$words = explode(' ', implode(' ', $words));
}

foreach ($words as $word) {
$tmp = ($line === null) ? $word : $line.' '.$word;


preg_match('/\p{Cf}/u', $word, $joinerMatches);

$wordWidth = count($joinerMatches) > 0 ? 2 : mb_strwidth($word);

$lineWidth += $wordWidth;

if ($line !== null) {

$lineWidth += 1;
}

if ($lineWidth <= $width) {
$line = $tmp;
} else {
$result[] = $line;
$line = $word;
$lineWidth = $wordWidth;
}
}

if ($line !== '') {
$result[] = $line;
}

$line = null;
}

return implode($break, $result);
}






protected function ansiWordwrap(string $text, int $width): array
{

$segments = $this->parseAnsiText($text);
$plainText = $this->stripEscapeSequences($text);
$chars = [];

foreach ($segments as $segment) {
$segmentChars = mb_str_split($segment['text']);

foreach ($segmentChars as $char) {
$chars[] = ['char' => $char, 'codes' => $segment['codes']];
}
}


$wrappedLines = $this->mbWordwrap($plainText, $width, "\n", false);
$plainLines = explode("\n", $wrappedLines);


$result = [];
$charIndex = 0;

foreach ($plainLines as $plainLine) {
$line = '';
$lastCodes = '';
$lineChars = mb_str_split($plainLine);

foreach ($lineChars as $lineChar) {

while ($charIndex < count($chars) && $chars[$charIndex]['char'] !== $lineChar) {

if ($chars[$charIndex]['char'] === ' ') {
$charIndex++;
} else {
break;
}
}

if ($charIndex < count($chars)) {
$codes = $chars[$charIndex]['codes'];

if ($codes !== $lastCodes) {
if ($lastCodes !== '') {
$line .= "\e[0m";
}

if ($codes !== '') {
$line .= $codes;
}

$lastCodes = $codes;
}

$line .= $lineChar;
$charIndex++;
} else {
$line .= $lineChar;
}
}


if ($lastCodes !== '' && ! str_ends_with($line, "\e[0m")) {
$line .= "\e[0m";
}

$result[] = $line;
}

return $result;
}






protected function parseAnsiText(string $text): array
{
$segments = [];
$currentCodes = '';
$currentText = '';
$i = 0;
$textLength = strlen($text);

while ($i < $textLength) {
if ($text[$i] === "\e" && ($i + 1 < $textLength) && $text[$i + 1] === '[') {

if ($currentText !== '') {
$segments[] = ['text' => $currentText, 'codes' => $currentCodes];
$currentText = '';
}


$escapeSequence = '';
while ($i < $textLength) {
$escapeSequence .= $text[$i];
$i++;

if (preg_match('/^\\e\\[[0-9;]*m$/', $escapeSequence)) {

if ($escapeSequence === "\e[0m") {
$currentCodes = '';
} else {
$currentCodes = $escapeSequence;
}
break;
}
}

continue;
}

$currentText .= $text[$i];
$i++;
}


if ($currentText !== '') {
$segments[] = ['text' => $currentText, 'codes' => $currentCodes];
}

return $segments;
}
}
