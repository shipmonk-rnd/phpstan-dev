# PHPStan Development Utilities

Development utilities for PHPStan rules testing, extracted from [shipmonk/phpstan-rules](https://github.com/shipmonk-rnd/phpstan-rules).

This package provides the `RuleTestCase` class - an enhanced testing framework specifically designed for testing PHPStan rules with additional validation and convenience features.

## Installation

```bash
composer require --dev shipmonk/phpstan-dev
```

## Quick Start

### 1. Create Your Rule Test Class

```php
<?php declare(strict_types = 1);

namespace YourNamespace;

use ShipMonk\PHPStanDev\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @extends RuleTestCase<YourRule>
 */
class YourRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new YourRule();
    }

    public function testRule(): void
    {
        $this->analyseFile(__DIR__ . '/data/YourRule/code.php');
    }
}
```

### 2. Create Test Data File

Create `tests/Rule/data/YourRule/code.php`:

```php
<?php declare(strict_types = 1);

namespace YourRule;

function test() {
    $valid = 'This is valid code';
    $invalid = something(); // error: Your custom error message here
}
```

### 3. Run Your Test

```bash
vendor/bin/phpunit tests/Rule/YourRuleTest.php
```

## Key Features

### 🎯 Error Comment System

Use `// error: <message>` comments in test files to specify expected errors:

```php
<?php

$validCode = 'No error expected here';
$invalidCode = forbidden(); // error: This is forbidden
$alsoInvalid = another(); // error: Another error message
```

### 🔧 Autofix Mode

During development, automatically generate error comments:

```php
public function testRule(): void
{
    // Set to true temporarily to generate error comments
    $this->analyseFile(__DIR__ . '/data/code.php', autofix: true);
}
```

**⚠️ Important**: Remove `autofix: true` before committing - tests will fail if autofix is enabled.

### 🛡️ Automatic Error Validation

Every error is automatically validated:
- ✅ Must have an identifier
- ✅ Identifier must start with `'shipmonk.'`
- ✅ Errors are matched to specific line numbers

## Advanced Usage

### Multiple Test Scenarios

```php
class ComplexRuleTest extends RuleTestCase
{
    private bool $strictMode = false;

    protected function getRule(): Rule
    {
        return new ComplexRule($this->strictMode);
    }

    public function testDefault(): void
    {
        $this->analyseFile(__DIR__ . '/data/ComplexRule/default.php');
    }

    public function testStrict(): void
    {
        $this->strictMode = true;
        $this->analyseFile(__DIR__ . '/data/ComplexRule/strict.php');
    }
}
```

### PHP Version-Specific Tests

```php
public function testPhp82Features(): void
{
    $this->phpVersion = $this->createPhpVersion(80_200);
    $this->analyseFile(__DIR__ . '/data/Rule/php82-features.php');
}
```

### Custom PHPStan Configuration

Create `tests/Rule/data/YourRule/config.neon`:

```neon
parameters:
    customParameter: value
```

Then reference it in your test:

```php
public static function getAdditionalConfigFiles(): array
{
    return array_merge(
        parent::getAdditionalConfigFiles(),
        [__DIR__ . '/data/YourRule/config.neon'],
    );
}
```

### Rules with Dependencies

```php
protected function getRule(): Rule
{
    $dependency = self::getContainer()->getByType(SomeService::class);
    return new RuleWithDependencies($dependency);
}
```

## File Organization

Recommended directory structure:

```
tests/
├── Rule/
│   ├── YourRuleTest.php
│   ├── AnotherRuleTest.php
│   └── data/
│       ├── YourRule/
│       │   ├── code.php           # Main test file
│       │   ├── edge-cases.php     # Additional scenarios
│       │   └── config.neon        # Optional PHPStan config
│       └── AnotherRule/
│           └── code.php
```

## Common Patterns

### Testing Different Error Scenarios

```php
<?php

// Valid usage - no error comment
$valid = getValue();

// Invalid usage with expected error
$invalid = badFunction(); // error: Function badFunction() is not allowed

// Multiple errors on same line
$a = bad1(); $b = bad2(); // error: Function bad1() is not allowed // error: Function bad2() is not allowed
```

### Testing Complex Error Messages

```php
<?php

function test($param) {
    // Error with variable content
    return $param + null; // error: Null value involved in binary operation: int + null

    // Error with specific types
    $array['key'] = $value; // error: Unsafe array key access, key type: mixed
}
```

## Error Comment Rules

1. **Format**: `// error: <message>`
2. **Placement**: At the end of the line that should trigger the error
3. **Multiple errors**: Use separate comments: `// error: First // error: Second`
4. **No error expected**: Don't add any comment
5. **Exact matching**: Error message must match exactly (whitespace sensitive)

## Development Workflow

### 1. Write the Rule

```php
class YourRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // Your rule logic
        return [
            RuleErrorBuilder::message('Error message')
                ->identifier('shipmonk.yourRule.errorType')
                ->build(),
        ];
    }
}
```

### 2. Create Test with Autofix

```php
public function testRule(): void
{
    $this->analyseFile(__DIR__ . '/data/code.php', autofix: true);
}
```

### 3. Run Test to Generate Comments

```bash
vendor/bin/phpunit tests/Rule/YourRuleTest.php
```

### 4. Review Generated Comments

Check the test data file - error comments will be automatically added.

### 5. Remove Autofix and Commit

```php
public function testRule(): void
{
    $this->analyseFile(__DIR__ . '/data/code.php'); // autofix: true removed
}
```

## Development

```bash
# Install dependencies
composer install

# Run all checks
composer check

# Individual checks
composer check:composer    # Validate composer.json
composer check:ec          # Check EditorConfig compliance
composer check:cs          # Check coding standards (PHPCS)
composer check:types       # Run PHPStan analysis
composer check:dependencies # Analyze dependencies
composer check:collisions  # Check for name collisions

# Fix coding standards
composer fix:cs
```

## Requirements

- PHP 7.4 or higher
- PHPStan 2.1.8 or higher
- PHPUnit 9.6.22 or higher (for testing)

## License

MIT License - see [LICENSE](LICENSE) file.
