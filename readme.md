##Recognition of temporary e-mail addresses as validator for Symfony

[![Latest Stable Version](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/v)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony) [![Total Downloads](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/downloads)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony) [![Latest Unstable Version](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/v/unstable)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony) [![License](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/license)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony)
[![composer.lock](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/composerlock)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony)
[![License](http://poser.pugx.org/marcel-strahl/temporary-email-validator-symfony/license)](https://packagist.org/packages/marcel-strahl/temporary-email-validator-symfony)

It is compatible and tested with PHP 8.+ on Symfony 5.x.

###Installation:

```bash
composer require marcel-strahl/temporary-email-validator
```
If Symfony/Flex is not enabled, the bundle will not be loaded automatically. So it is necessary to enter this in the `config/bundes.php` yourself, that you can do with the following line:
```php
return [
    // Some other bundles [...]
    MarcelStrahl\TemporaryEmailValidatorSymfony\TemporaryEmailValidatorBundle::class => ['all' => true],  
];
```
You can easily use the validator via annotation with the help of the `doctrine/annotations` bundle.
The following is an annotation example:
```php
namespace App\Controller;

use MarcelStrahl\TemporaryEmailValidatorSymfony\Validator\IsNotTemporaryEmail;

final class TestDto
{
    /**
    * @IsNotTemporaryEmail 
    */
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
```

The following is an attribute example:
```php
     namespace App\Controller;

use MarcelStrahl\TemporaryEmailValidatorSymfony\Validator\IsNotTemporaryEmail;

final class TestDto
{
    #[IsNotTemporaryEmail]
    private string $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
```

###Hint:

I am not the creator of the "Temporary Email Detection" but have changed the following package into a Symfony Validator!

Main Package Temporary E-Mail Detection: https://github.com/jprangenbergde/temporary-email-detection

Credits
-------

* Marcel Strahl <info@marcel-strahl.de>


License
-------

This bundle is under the MIT license.  
For the whole copyright, see the [LICENSE](LICENSE) file distributed with this source code.
