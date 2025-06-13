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
        $this->analyseFile(__DIR__ . '/Data/YourRule/code.php');
    }
}
```

### 2. Create Test Data File

Create `tests/Rule/Data/YourRule/code.php`:

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
    $this->analyseFile(__DIR__ . '/Data/code.php', autofix: true);
}
```

**⚠️ Important**: Remove `autofix: true` before committing - tests will fail if autofix is enabled.

### 🛡️ Automatic Error Validation

Every error is automatically validated:
- ✅ Must have an identifier
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
        $this->analyseFile(__DIR__ . '/Data/ComplexRule/default.php');
    }

    public function testStrict(): void
    {
        $this->strictMode = true;
        $this->analyseFile(__DIR__ . '/Data/ComplexRule/strict.php');
    }
}
```

### PHP Version-Specific Tests

```php
public function testPhp82Features(): void
{
    $this->phpVersion = $this->createPhpVersion(80_200);
    $this->analyseFile(__DIR__ . '/Data/Rule/php82-features.php');
}
```

### Custom PHPStan Configuration

Create `tests/Rule/Data/YourRule/config.neon`:

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
        [__DIR__ . '/Data/YourRule/config.neon'],
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
│   └── Data/
│       ├── YourRule/
│       │   ├── code.php           # Main test file
│       │   ├── edge-cases.php     # Additional scenarios
│       │   └── config.neon        # Optional PHPStan config
│       └── AnotherRule/
│           └── code.php
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

## License

MIT License - see [LICENSE](LICENSE) file.
