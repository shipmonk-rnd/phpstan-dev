<?php declare(strict_types = 1);

namespace ShipMonkTests\PHPStanDev;

use PHPStan\Rules\Rule;
use PHPUnit\Framework\AssertionFailedError;
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

    public function testTrait(): void
    {
        $this->analyzeFiles([
            __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/trait.php',
            __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/trait-user.php',
        ]);
    }

    public function testMultipleErrorsOnSameLine(): void
    {
        // Create a dedicated test file for multiple errors demonstration
        $testFile = __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/multiple-errors.php';

        $this->analyzeFiles([$testFile]);
    }

    public function testAutofix(): void
    {
        $expectedFile = __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/autofix.expected.php';
        $testFile = __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/autofix.php';
        $tmpFile = sys_get_temp_dir() . '/autofix.php';
        copy($testFile, $tmpFile);

        try {
            $this->analyzeFiles([$tmpFile], true);
            self::fail('Autofix should have thrown an exception');
        } catch (AssertionFailedError $e) { // @phpstan-ignore catch.internalClass
            self::assertStringContainsString('autofixed', $e->getMessage());
        }

        self::assertFileEquals($expectedFile, $tmpFile);
    }

    public function testErrorMessageFormat(): void
    {
        $testFile = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/Rule/Data/DisallowDivisionByLiteralZeroRule/error-message.php');

        try {
            $this->analyzeFiles([$testFile]);
            self::fail('Expected assertion to fail');
        } catch (AssertionFailedError $e) { // @phpstan-ignore catch.internalClass
            $expectedMessage = <<<MSG
                Errors in file {$testFile} do not match:

                New errors reported:
                08: Division by literal zero is not allowed

                Errors not reported:
                08: This error should not be reported
                09: Division by literal zero is not allowed


                MSG;
            self::assertStringContainsString($expectedMessage, $e->getMessage());
        }
    }

}
