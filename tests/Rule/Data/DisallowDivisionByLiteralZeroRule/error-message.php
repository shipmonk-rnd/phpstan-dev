<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testErrorMessage(): void
{
    $a = 10;
    $invalidDivision = $a / 0; // error: This error should not be reported
    $validDivision = $a / 2; // error: Division by literal zero is not allowed
}
