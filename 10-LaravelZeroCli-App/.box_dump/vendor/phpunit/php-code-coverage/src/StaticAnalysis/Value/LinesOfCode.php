<?php declare(strict_types=1);








namespace SebastianBergmann\CodeCoverage\StaticAnalysis;




final readonly class LinesOfCode
{



private int $linesOfCode;




private int $commentLinesOfCode;




private int $nonCommentLinesOfCode;






public function __construct(int $linesOfCode, int $commentLinesOfCode, int $nonCommentLinesOfCode)
{
$this->linesOfCode = $linesOfCode;
$this->commentLinesOfCode = $commentLinesOfCode;
$this->nonCommentLinesOfCode = $nonCommentLinesOfCode;
}




public function linesOfCode(): int
{
return $this->linesOfCode;
}




public function commentLinesOfCode(): int
{
return $this->commentLinesOfCode;
}




public function nonCommentLinesOfCode(): int
{
return $this->nonCommentLinesOfCode;
}
}
