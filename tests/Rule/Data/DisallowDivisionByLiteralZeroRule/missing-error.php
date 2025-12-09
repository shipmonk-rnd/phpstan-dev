<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testMissingError(): void
{
    $a = 10;
    $validDivision = $a / 2; // error: Division by literal zero is not allowed
}
