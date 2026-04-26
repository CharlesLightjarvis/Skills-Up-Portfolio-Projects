<?php










namespace Symfony\Component\VarDumper\Dumper;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Cloner\DumperInterface;






abstract class AbstractDumper implements DataDumperInterface, DumperInterface
{
public const DUMP_LIGHT_ARRAY = 1;
public const DUMP_STRING_LENGTH = 2;
public const DUMP_COMMA_SEPARATOR = 4;
public const DUMP_TRAILING_COMMA = 8;


public static $defaultOutput = 'php://output';

protected string $line = '';

protected $lineDumper;

protected $outputStream;
protected string $decimalPoint = '.';
protected string $indentPad = '  ';

private string $charset = '';






public function __construct(
$output = null,
?string $charset = null,
protected int $flags = 0,
) {
$this->setCharset($charset ?: \ini_get('php.output_encoding') ?: \ini_get('default_charset') ?: 'UTF-8');
$this->setOutput($output ?: static::$defaultOutput);
if (!$output && \is_string(static::$defaultOutput)) {
static::$defaultOutput = $this->outputStream;
}
}








public function setOutput($output)
{
$prev = $this->outputStream ?? $this->lineDumper;

if (\is_callable($output)) {
$this->outputStream = null;
$this->lineDumper = $output;
} else {
if (\is_string($output)) {
$output = fopen($output, 'w');
}
$this->outputStream = $output;
$this->lineDumper = $this->echoLine(...);
}

return $prev;
}






public function setCharset(string $charset): string
{
$prev = $this->charset;

$charset = strtoupper($charset);
$charset = 'UTF-8' === $charset || 'UTF8' === $charset ? 'CP1252' : $charset;

$this->charset = $charset;

return $prev;
}








public function setIndentPad(string $pad): string
{
$prev = $this->indentPad;
$this->indentPad = $pad;

return $prev;
}








public function dump(Data $data, $output = null): ?string
{
if ($locale = $this->flags & (self::DUMP_COMMA_SEPARATOR | self::DUMP_TRAILING_COMMA) ? setlocale(\LC_NUMERIC, 0) : null) {
setlocale(\LC_NUMERIC, 'C');
}

if ($returnDump = true === $output) {
$output = fopen('php://memory', 'r+');
}
if ($output) {
$prevOutput = $this->setOutput($output);
}
try {
$data->dump($this);
$this->dumpLine(-1);

if ($returnDump) {
$result = stream_get_contents($output, -1, 0);
fclose($output);

return $result;
}
} finally {
if ($output) {
$this->setOutput($prevOutput);
}
if ($locale) {
setlocale(\LC_NUMERIC, $locale);
}
}

return null;
}







protected function dumpLine(int $depth): void
{
($this->lineDumper)($this->line, $depth, $this->indentPad);
$this->line = '';
}




protected function echoLine(string $line, int $depth, string $indentPad): void
{
if (-1 !== $depth) {
fwrite($this->outputStream, str_repeat($indentPad, $depth).$line."\n");
}
}




protected function utf8Encode(?string $s): ?string
{
if (null === $s || preg_match('//u', $s)) {
return $s;
}

if (\function_exists('iconv')) {
if (false !== $c = @iconv($this->charset, 'UTF-8', $s)) {
return $c;
}
if ('CP1252' !== $this->charset && false !== $c = @iconv('CP1252', 'UTF-8', $s)) {
return $c;
}
}

$s .= $s;
$len = \strlen($s);
$mapCp1252 = false;

for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
if ($s[$i] < "\x80") {
$s[$j] = $s[$i];
} elseif ($s[$i] < "\xC0") {
$s[$j] = "\xC2";
$s[++$j] = $s[$i];
if ($s[$i] < "\xA0") {
$mapCp1252 = true;
}
} else {
$s[$j] = "\xC3";
$s[++$j] = \chr(\ord($s[$i]) - 64);
}
}

$s = substr($s, 0, $j);

if (!$mapCp1252) {
return $s;
}

return strtr($s, [
"\xC2\x80" => '€', "\xC2\x82" => '‚', "\xC2\x83" => 'ƒ', "\xC2\x84" => '„',
"\xC2\x85" => '…', "\xC2\x86" => '†', "\xC2\x87" => '‡', "\xC2\x88" => 'ˆ',
"\xC2\x89" => '‰', "\xC2\x8A" => 'Š', "\xC2\x8B" => '‹', "\xC2\x8C" => 'Œ',
"\xC2\x8D" => 'Ž', "\xC2\x91" => '‘', "\xC2\x92" => '’', "\xC2\x93" => '“',
"\xC2\x94" => '”', "\xC2\x95" => '•', "\xC2\x96" => '–', "\xC2\x97" => '—',
"\xC2\x98" => '˜', "\xC2\x99" => '™', "\xC2\x9A" => 'š', "\xC2\x9B" => '›',
"\xC2\x9C" => 'œ', "\xC2\x9E" => 'ž',
]);
}
}
