<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testMultipleErrorsPerLine(): void
{
    $a = 10;

    // Line with two errors
    $twoErrors = ($a / 0) + (5 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed

    // Line with three errors
    $threeErrors = ($a / 0) + (5 / 0) + (10 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed

    // Line with four errors
    $fourErrors = ($a / 0) + (5 / 0) + (10 / 0) + (15 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed

    // Mixed valid and invalid operations (only invalid ones should error)
    $mixed = ($a / 2) + (5 / 0) + ($a / 3) + (10 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed
}

