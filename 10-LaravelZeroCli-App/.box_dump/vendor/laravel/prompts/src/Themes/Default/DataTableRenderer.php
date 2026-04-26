<?php

namespace Laravel\Prompts\Themes\Default;

use Laravel\Prompts\DataTablePrompt;
use Laravel\Prompts\Themes\Contracts\Scrolling;
use Laravel\Prompts\Themes\Default\Concerns\DrawsBoxes;
use Laravel\Prompts\Themes\Default\Concerns\DrawsScrollbars;

class DataTableRenderer extends Renderer implements Scrolling
{
use DrawsBoxes;
use DrawsScrollbars;




public function __invoke(DataTablePrompt $prompt): string
{
$maxWidth = $prompt->terminal()->cols() - 6;

return match ($prompt->state) {
'submit' => $this->renderSubmit($prompt, $maxWidth),
'cancel' => $this->renderCancel($prompt, $maxWidth),
default => $this->renderActive($prompt, $maxWidth),
};
}




protected function renderSubmit(DataTablePrompt $prompt, int $maxWidth): string
{
$row = $prompt->selectedRow();
$display = $row ? $this->truncate(implode(', ', $row), $maxWidth) : '';

return $this
->box(
$this->dim($this->truncate($prompt->label, $maxWidth)),
$display,
);
}




protected function renderCancel(DataTablePrompt $prompt, int $maxWidth): string
{
$filtered = $prompt->filteredRows();
$visible = $prompt->visible();

$numCols = ! empty($prompt->headers)
? count($prompt->headers)
: max(array_map('count', $prompt->rows));

$widths = $this->computeColumnWidths($prompt->headers, $prompt->rows, $numCols, $maxWidth);
$innerWidth = array_sum($widths) + ($numCols * 2) + ($numCols - 1) + 2;


$titleText = $this->truncate($prompt->label, $maxWidth);
$titleLength = mb_strwidth($this->stripEscapeSequences($titleText));
$topBorderFill = max(0, $innerWidth - $titleLength - 2);
$this->line($this->red(' ┌')." {$titleText} ".$this->red(str_repeat('─', $topBorderFill).'┐'));


$searchContent = $this->renderSearchLine($prompt, $innerWidth - 2);
$this->line($this->red(' │').' '.$this->dim($this->pad($searchContent, $innerWidth - 2)).' '.$this->red('│'));


$this->line(' '.$this->renderBorder('├', '┬', '┤', $widths, 'red'));


if (! empty($prompt->headers)) {
$headerCells = [];

foreach ($widths as $i => $w) {
$header = $prompt->headers[$i] ?? '';
$text = is_array($header) ? implode(' ', $header) : $header;
$headerCells[] = $this->dim(' '.$this->pad($this->strikethrough($this->truncate($text, $w)), $w).' ');
}

$headerLine = implode($this->red('│'), $headerCells).'  ';
$this->line($this->red(' │').$this->pad($headerLine, $innerWidth).$this->red('│'));

$this->line(' '.$this->renderBorder('├', '┼', '┤', $widths, 'red'));
}


$dataLines = $this->renderDataRows($prompt, $filtered, $visible, $widths, $numCols, $innerWidth, strikethrough: true);

foreach ($dataLines as $dataLine) {
$this->line($this->red(' │').$this->pad($dataLine, $innerWidth).$this->red('│'));
}


$this->line(' '.$this->renderBorder('└', '┴', '┘', $widths, 'red'));

return $this->error($prompt->cancelMessage);
}




protected function renderActive(DataTablePrompt $prompt, int $maxWidth): string
{
$filtered = $prompt->filteredRows();
$total = count($filtered);
$visible = $prompt->visible();

$numCols = ! empty($prompt->headers)
? count($prompt->headers)
: max(array_map('count', $prompt->rows));


$widths = $this->computeColumnWidths($prompt->headers, $prompt->rows, $numCols, $maxWidth);



$innerWidth = array_sum($widths) + ($numCols * 2) + ($numCols - 1) + 2;


$titleText = $this->cyan($this->truncate($prompt->label, $maxWidth));
$titleLength = mb_strwidth($this->stripEscapeSequences($titleText));
$topBorderFill = max(0, $innerWidth - $titleLength - 2);
$this->line($this->gray(' ┌')." {$titleText} ".$this->gray(str_repeat('─', $topBorderFill).'┐'));


$searchContent = $this->renderSearchLine($prompt, $innerWidth - 2);
$this->line($this->gray(' │').' '.$this->pad($searchContent, $innerWidth - 2).' '.$this->gray('│'));

if ($total === 0) {

$this->line(' '.$this->renderSimpleBorder('├', '┤', $innerWidth));

$message = $prompt->searchValue() !== '' ? 'No results found.' : 'No rows.';
$emptyLine = $this->pad(' '.$this->dim($message), $innerWidth);
$this->line($this->gray(' │').$this->pad($emptyLine, $innerWidth).$this->gray('│'));

$this->line(' '.$this->renderSimpleBorder('└', '┘', $innerWidth));
} else {

$this->line(' '.$this->renderBorder('├', '┬', '┤', $widths));


if (! empty($prompt->headers)) {
$headerCells = [];

foreach ($widths as $i => $w) {
$header = $prompt->headers[$i] ?? '';
$text = is_array($header) ? implode(' ', $header) : $header;
$headerCells[] = $this->dim(' '.$this->pad($this->truncate($text, $w), $w).' ');
}

$headerLine = implode($this->gray('│'), $headerCells).'  ';
$this->line($this->gray(' │').$this->pad($headerLine, $innerWidth).$this->gray('│'));


$this->line(' '.$this->renderBorder('├', '┼', '┤', $widths));
}


$dataLines = $this->renderDataRows($prompt, $filtered, $visible, $widths, $numCols, $innerWidth);

foreach ($dataLines as $dataLine) {
$this->line($this->gray(' │').$this->pad($dataLine, $innerWidth).$this->gray('│'));
}


$this->line(' '.$this->renderBorder('└', '┴', '┘', $widths));


if ($total > $prompt->scroll) {
$firstRow = $prompt->firstVisible + 1;
$lastRow = min($prompt->firstVisible + $prompt->scroll, $total);
$suffix = $prompt->searchValue() !== '' ? ' results' : '';
$info = $this->dim('  Viewing ').$firstRow.'-'.$lastRow.$this->dim(' of ').$total.$suffix;
$this->line($info);
}
}

return $this
->when(
$prompt->state === 'error',
fn () => $this->warning($this->truncate($prompt->error, $prompt->terminal()->cols() - 5)),
fn () => $this->when(
$prompt->hint,
fn () => $this->hint($prompt->hint),
fn () => $this->newLine(),
),
);
}






protected function renderBorder(string $left, string $mid, string $right, array $widths, string $color = 'gray'): string
{
$segments = array_map(fn ($w) => str_repeat('─', $w + 2), $widths);

return $this->{$color}($left.implode($mid, $segments).'──'.$right);
}




protected function renderSimpleBorder(string $left, string $right, int $innerWidth, string $color = 'gray'): string
{
return $this->{$color}($left.str_repeat('─', $innerWidth).$right);
}




protected function renderSearchLine(DataTablePrompt $prompt, int $maxWidth): string
{
if ($prompt->state === 'search') {
return $this->cyan('/').' '.$prompt->searchWithCursor($maxWidth - 4);
}

if ($prompt->searchValue() !== '') {
return $this->dim('/').' '.$prompt->searchValue();
}

return $this->dim('/ Search');
}









protected function renderDataRows(DataTablePrompt $prompt, array $filtered, array $visible, array $widths, int $numCols, int $innerWidth, bool $strikethrough = false): array
{
$total = count($filtered);


$emptyRow = implode($this->gray('│'), array_map(
fn ($w) => str_repeat(' ', $w + 2),
$widths,
)).'  ';

$highlightedKey = array_keys($filtered)[$prompt->highlighted] ?? null;
$isSearching = $prompt->state === 'search';
$fixedHeight = $prompt->scroll;



$taggedLines = [];

foreach ($visible as $key => $row) {
$isHighlighted = ! $isSearching && ! $strikethrough && $key === $highlightedKey;


$cellLines = [];
$maxSubRows = 1;

foreach ($widths as $i => $w) {
$text = $row[$i] ?? '';
$subLines = explode(PHP_EOL, $text);
$cellLines[$i] = $subLines;
$maxSubRows = max($maxSubRows, count($subLines));
}


for ($subRow = 0; $subRow < $maxSubRows; $subRow++) {
$cells = [];

foreach ($widths as $i => $w) {
$text = $cellLines[$i][$subRow] ?? '';
$content = ' '.$this->pad($this->truncate($text, $w), $w).' ';

if ($strikethrough) {
$content = ' '.$this->pad($this->dim($this->strikethrough($this->truncate($text, $w))), $w).' ';
} elseif ($isHighlighted) {
$content = $this->inverse($content);
} elseif ($isSearching) {
$content = $this->dim($content);
}

$cells[] = $content;
}

$separator = $isHighlighted ? $this->inverse('│') : $this->gray('│');
$taggedLines[] = [
'line' => implode($separator, $cells).'  ',
'highlighted' => $isHighlighted,
];
}
}




$totalVisual = count($taggedLines);

if ($totalVisual <= $fixedHeight) {
$dataLines = array_column($taggedLines, 'line');
} else {

$hlStart = null;
$hlEnd = null;

foreach ($taggedLines as $i => $tagged) {
if ($tagged['highlighted']) {
$hlStart ??= $i;
$hlEnd = $i;
}
}



if ($hlStart !== null) {
$startLine = max(0, $hlEnd - $fixedHeight + 1);
$startLine = min($startLine, $hlStart);
} else {
$startLine = 0;
}

$startLine = min($startLine, $totalVisual - $fixedHeight);
$startLine = max(0, $startLine);

$dataLines = array_column(array_slice($taggedLines, $startLine, $fixedHeight), 'line');
}

while (count($dataLines) < $fixedHeight) {
$dataLines[] = $emptyRow;
}






$shouldScroll = $total > $prompt->scroll;

if ($shouldScroll) {
$numVisual = count($dataLines);
$maxFirst = $total - $prompt->scroll;

if ($prompt->firstVisible === 0) {
$visualPos = 0;
} elseif ($prompt->firstVisible >= $maxFirst) {
$visualPos = $numVisual - 1;
} elseif ($numVisual <= 2) {
$visualPos = -1;
} else {
$percent = $prompt->firstVisible / $maxFirst;
$visualPos = (int) round($percent * ($numVisual - 3)) + 1;
}

$dataLines = array_map(fn ($line, $index) => match ($index) {
$visualPos => preg_replace('/.$/', $this->cyan('┃'), $this->pad($line, $innerWidth)) ?? '',
default => preg_replace('/.$/', $this->gray('│'), $this->pad($line, $innerWidth)) ?? '',
}, array_values($dataLines), range(0, $numVisual - 1));
}

return $dataLines;
}











protected function computeColumnWidths(array $headers, array $allRows, int $numCols, int $maxWidth): array
{

$headerWidths = array_fill(0, $numCols, 0);

foreach ($headers as $i => $header) {
$headerText = is_array($header) ? implode(' ', $header) : $header;
$headerWidths[$i] = mb_strwidth($headerText);
}


$columnWidths = array_fill(0, $numCols, []);

foreach ($allRows as $row) {
foreach ($row as $i => $cell) {
$cellMax = 0;
foreach (explode(PHP_EOL, $cell) as $line) {
$cellMax = max($cellMax, mb_strwidth($line));
}
if ($cellMax > 0) {
$columnWidths[$i][] = $cellMax;
}
}
}




$natural = array_fill(0, $numCols, 0);

foreach ($columnWidths as $i => $widths) {
if (empty($widths)) {
$natural[$i] = $headerWidths[$i];

continue;
}

sort($widths);
$p90Index = (int) ceil(count($widths) * 0.90) - 1;
$p90 = $widths[max(0, $p90Index)];
$colMax = end($widths);

$natural[$i] = max($headerWidths[$i], $colMax <= $p90 * 2 ? $colMax : $p90);
}






$overhead = ($numCols * 2) + ($numCols - 1) + 2 + 4;
$available = $maxWidth - $overhead;

if ($available <= 0) {
return array_fill(0, $numCols, 1);
}

$totalNatural = array_sum($natural);


if ($totalNatural <= $available) {
return $natural;
}


$widths = array_fill(0, $numCols, 0);

foreach ($natural as $i => $w) {
$widths[$i] = max($headerWidths[$i], (int) floor($available * $w / $totalNatural));
}


$remainder = $available - array_sum($widths);

if ($remainder > 0) {
$order = range(0, $numCols - 1);
usort($order, fn ($a, $b) => $natural[$b] <=> $natural[$a]);

foreach ($order as $i) {
if ($remainder <= 0) {
break;
}
$widths[$i]++;
$remainder--;
}
}

return $widths;
}




public function reservedLines(): int
{
return 10;
}
}
