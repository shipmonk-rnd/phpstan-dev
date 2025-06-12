<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testDivision(): void
{
    $a = 10;
    $b = 0;

    $validDivision = $a / 2;
    $validDivisionWithVariable = $a / $b;

    $invalidDivision = $a / 0; // error: Division by literal zero is not allowed
    $anotherInvalidDivision = 5 / 0; // error: Division by literal zero is not allowed

    // Test multiple errors on same line - each gets detected separately
    $multipleErrors = ($a / 0) + (5 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed

    // Test three errors on one line
    $threeErrors = ($a / 0) + (5 / 0) + (10 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed

    // Test mixed valid/invalid on same line (should only have one error)
    $mixedLine = ($a / 2) + (5 / 0); // error: Division by literal zero is not allowed
}
