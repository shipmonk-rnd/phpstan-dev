<?php declare(strict_types = 1);

namespace ShipMonkTests\PHPStanDev;

use PHPStan\Rules\Rule;
use ShipMonk\PHPStanDev\RuleTestCase;
use ShipMonkTests\PHPStanDev\Rule\Data\DisallowDivisionByLiteralZeroRule\DisallowDivisionByLiteralZeroRule;

/**
 * @extends RuleTestCase<DisallowDivisionByLiteralZeroRule>
 */
class RuleTestCaseTest extends RuleTestCase
{

    protected function getRule(): Rule
    {
        return new DisallowDivisionByLiteralZeroRule();
    }

    public function testRule(): void
    {
        $this->analyzeFiles([__DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/code.php']);
    }

    public function testMultipleErrorsOnSameLine(): void
    {
        // Create a dedicated test file for multiple errors demonstration
        $testFile = __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/multiple-errors.php';

        $this->analyzeFiles([$testFile]);
    }

}
