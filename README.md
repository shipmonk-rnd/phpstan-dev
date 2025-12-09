# PHPStan Rules Development Utilities

Inplace fixture asserts and autofix for PHPStan rules.
No more manual line adjustments in tests when new code is added to rule fixtures.

## Installation

```bash
composer require --dev shipmonk/phpstan-dev
```

## Usage

```php
<?php declare(strict_types = 1);

use PHPStan\Rules\Rule;

/**
 * @extends RuleTestCase<YourRule>
 */
class YourRuleTest extends \ShipMonk\PHPStanDev\RuleTestCase
{
    public function testRule(): void
    {
        $this->analyzeFiles([__DIR__ . '/Data/code.php']);
    }
}
```

Create test fixture at `code.php`:

```php
<?php

$valid = 'This is valid code';
$invalid = something(); // error: Rule error message
```

## Key Features

### In-fixture error asserts of  via `// error:`

Mark expected errors directly in test files via PHP comments:

```php
<?php

$validCode = 'No error expected here';
$invalidCode = forbidden(); // error: Rule error message
$alsoInvalid = another(); // error: Rule error message // error: Same-line multi errors
```

### Autofix

Automatically generate inplace error comments during development:

```php
public function testRule(): void
{
    $this->analyzeFiles([...], autofix: true);
}
```

**⚠️ Important**: Remove `autofix: true` before committing - tests will fail if autofix is enabled.

## Contributing
- Check your code by `composer check`
- Autofix coding-style by `composer fix:cs`
- All functionality must be tested


## License

MIT
