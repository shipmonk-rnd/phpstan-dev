<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

function testDivisionAutofix(): void
{
    1 / 0; // error: Division by literal zero is not allowed
    1 / 0; // error: Division by literal zero is not allowed
    1 / 0; // error: Division by literal zero is not allowed
    1 / 1;
    1 / 1;

    ($a / 0) + (5 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed
    ($a / 0) + (5 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed
    ($a / 0) + (5 / 0); // error: Division by literal zero is not allowed // error: Division by literal zero is not allowed
}
