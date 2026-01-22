<?php declare(strict_types = 1);

namespace DisallowDivisionByLiteralZeroRule;

trait MyTrait
{
    public function emitError(): float
    {
        return 10 / 0; // error: Division by literal zero is not allowed
    }
}

