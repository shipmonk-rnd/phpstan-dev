<?php declare(strict_types = 1);

namespace ShipMonkTests\PHPStanDev\Rule\Data\DisallowDivisionByLiteralZeroRule;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Scalar\Int_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Div>
 */
class DisallowDivisionByLiteralZeroRule implements Rule
{

    public function getNodeType(): string
    {
        return Div::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->right instanceof Int_ && $node->right->value === 0) {
            return [
                RuleErrorBuilder::message('Division by literal zero is not allowed')
                    ->identifier('shipmonk.divisionByZero')
                    ->build(),
            ];
        }

        return [];
    }

}
