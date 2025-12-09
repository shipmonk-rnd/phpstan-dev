<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testExtraError(): void
{
    $a = 10;
    $invalidDivision = $a / 0;
}
