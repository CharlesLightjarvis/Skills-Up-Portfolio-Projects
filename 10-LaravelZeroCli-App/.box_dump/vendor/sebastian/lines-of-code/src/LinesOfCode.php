<?php declare(strict_types=1);








namespace SebastianBergmann\LinesOfCode;

/**
@immutable
*/
final readonly class LinesOfCode
{



private int $linesOfCode;




private int $commentLinesOfCode;




private int $nonCommentLinesOfCode;




private int $logicalLinesOfCode;









public function __construct(int $linesOfCode, int $commentLinesOfCode, int $nonCommentLinesOfCode, int $logicalLinesOfCode)
{
if ($linesOfCode - $commentLinesOfCode !== $nonCommentLinesOfCode) {
throw new IllogicalValuesException('$linesOfCode !== $commentLinesOfCode + $nonCommentLinesOfCode');
}

$this->linesOfCode = $linesOfCode;
$this->commentLinesOfCode = $commentLinesOfCode;
$this->nonCommentLinesOfCode = $nonCommentLinesOfCode;
$this->logicalLinesOfCode = $logicalLinesOfCode;
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




public function logicalLinesOfCode(): int
{
return $this->logicalLinesOfCode;
}

public function plus(self $other): self
{
return new self(
$this->linesOfCode() + $other->linesOfCode(),
$this->commentLinesOfCode() + $other->commentLinesOfCode(),
$this->nonCommentLinesOfCode() + $other->nonCommentLinesOfCode(),
$this->logicalLinesOfCode() + $other->logicalLinesOfCode(),
);
}
}
