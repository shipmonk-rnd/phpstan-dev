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
}

